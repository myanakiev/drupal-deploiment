<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\NodeInterface;

class HelloHistory extends ControllerBase {

    public function getContent(NodeInterface $node) {
        // https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Render%21Element%21Table.php/class/Table/8.2.x
        // https://api.drupal.org/api/drupal/core%21modules%21aggregator%21src%21Controller%21AggregatorController.php/function/AggregatorController%3A%3AadminOverview/8.2.x

        $conn = \Drupal::database();
        $sth = $conn->select('hello_node_history', 'hnh')
                ->fields('hnh', array('uid', 'update_time'))
                ->condition('hnh.nid', $node->id(), '=');

        // Execute the statement
        $data = $sth->execute();

        // Get all the results
        $results = $data->fetchAll(\PDO::FETCH_OBJ);

        // Iterate results
        $rows = array();
        $storage = \Drupal::entityTypeManager()->getStorage('user');
        foreach ($results as $row) {
            $user = $storage->load($row->uid);
            $time = \Drupal::service('date.formatter')->format($row->update_time, 'custom', 'l jS \of F Y h:i:s A');

            $rows[] = [empty($user->getAccountName()) ? 'anonymous' : $user->toLink(), $time];
        }
        
        $build_upd = array(
            '#theme' => 'hello_node_history',
            '#node' => $node->getTitle(),
            '#count' => count($results),
        );

        $build = array(
            '#prefix' => '<h3>' . $this->t('History overview') . '</h3>',
            '#type' => 'table',
            '#header' => array($this->t('Update author'), $this->t('Update time')),
            '#rows' => $rows,
            '#empty' => $this->t('No history available.'),
        );

        return [$build, $build_upd];
    }

}
