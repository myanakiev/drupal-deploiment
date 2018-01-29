<?php

namespace Drupal\annonce\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class TestSubscriber.
 */
class TestSubscriber implements EventSubscriberInterface {

    /**
     * Drupal\Core\Session\AccountProxy definition.
     *
     * @var \Drupal\Core\Session\AccountProxy
     */
    protected $currentUser;

    /**
     * Drupal\Core\Database\Driver\mysql\Connection definition.
     *
     * @var \Drupal\Core\Database\Driver\mysql\Connection
     */
    protected $database;

    /**
     * Drupal\Core\Datetime\DateFormatter definition.
     *
     * @var \Drupal\Core\Datetime\DateFormatter
     */
    protected $dateFormatter;

    /**
     * Constructs a new TestSubscriber object.
     */
    public function __construct(AccountProxy $current_user, Connection $database, DateFormatter $date_formatter, CurrentRouteMatch $current_route_match) {
        $this->currentUser = $current_user;
        $this->database = $database;
        $this->dateFormatter = $date_formatter;
        $this->current_route_match = $current_route_match;
    }

    /**
     * {@inheritdoc}
     */
    static function getSubscribedEvents() {
        $events['kernel.request'] = ['onKernelRequest'];

        return $events;
    }

    /**
     * This method is called whenever the kernel.request event is
     * dispatched.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(Event $event) {
        $annonce = $this->current_route_match->getParameter('annonce');
        $route = $this->current_route_match->getRouteName();
        $account = $this->currentUser;
        $database = $this->database;

        if ($annonce && ($route == 'entity.annonce.canonical')) {
            if ($account->id() && $account->id() == $annonce->getOwnerId() && $account->hasPermission('view published annonce entities')) {
                drupal_set_message('Event Access for Entité annonce ' . $account->getAccountName(), 'status', TRUE);
                $database->insert('annonce_history')->fields(
                        array(
                            'aid' => $annonce->id(),
                            'uid' => $account->id(),
                            'date' => time(),
                            'acl' => 'access',
                        )
                )->execute();
            }
        } elseif ($route == 'system.403') {
            $uid = ($account && $account->id()) ? $account->id() : 0;
            $aid = ($annonce && $annonce->id()) ? $annonce->id() : 0;
            $database->insert('annonce_history')->fields(
                    array(
                        'aid' => $aid,
                        'uid' => $uid,
                        'date' => time(),
                        'acl' => 'forbidden',
                    )
            )->execute();
            drupal_set_message('Event Forbidden for Entité annonce : ' . (empty($account->getAccountName()) ? 'anonymous' : $account->getAccountName()), 'error', TRUE);
        }
    }

}
