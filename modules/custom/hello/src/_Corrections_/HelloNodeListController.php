<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloNodeListController extends ControllerBase {

    public function content($nodetype) {
        $query = $this->entityTypeManager()->getStorage('node')->getQuery();
        // $query = $this->entityQuery('node');
        // Si on a un argument dans l'URL, on ne cible que les noeuds correspondants.
        if ($nodetype) {
            $query->condition('type', $nodetype);
        }
        // On construit une requête paginée.
        $nids = $query->execute();
        // Charge les noeuds correspondants au résultat de la requête.
        $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);

        // Construit un tableau de liens vers les noeuds.
        $items = array();
        foreach ($nodes as $node) {
            $items[] = $node->toLink();
        }
        $list = array(
            '#theme' => 'item_list',
            '#items' => $items,
        );
        return $list;
    }
}
