<?php

//https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Database%21database.api.php/group/database/8.2.x
//https://www.drupal.org/docs/8/api/database-api/dynamic-queries/fields
//https://www.drupal.org/docs/7/api/database-api/dynamic-queries/count-queries

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a Session Bloc.
 *
 * @Block(
 *  id = "session_block",
 *  admin_label = @Translation("Session Block")
 * )
 */
class SessionBloc extends BlockBase {

    public function build() {
        $dbdb = \Drupal::database();
        $sess = $dbdb->select('sessions')->countQuery()->execute()->fetchField();
        //$sear = $dbdb->select('sessions')->countQuery()->execute()->fetchRow();

        $vars = array('%session' => $sess);

        $build = [];
        $build['#markup'] = $this->t('Il y a actuellement %session sessions actives.', $vars);
        $build['#title'] = $this->t('Active Sessions');
        $build['#cache'] = [
            'keys' => ['build_block_session'],
            //'max-age' => 1, 
            //'contexts' => ['session'],
            'tags' => ['session'],
        ];

        return $build;
    }

    protected function blockAccess(AccountInterface $account) {
        return AccessResult::allowedIfHasPermission($account, 'active_sessions_permission');
        
        /*
        if($account->hasPermission('active_sessions_permission')) {
            return AccessResult::allowed();
        }
        
        return AccessResult::forbidden();
         */
    }

}
