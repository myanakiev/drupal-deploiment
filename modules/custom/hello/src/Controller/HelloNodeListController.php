<?php

//http://user8.d8.lab/hello-list
//http://user8.d8.lab/hello-list/page
//http://user8.d8.lab/hello-list/toto

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloNodeListController extends ControllerBase {

    public function getTitle($param) {
        return $this->t('Liste: %node', array('%node' => $param));
    }

    public function getContent($param) {
        $query = \Drupal::entityQuery('node');
        $query->condition('status', 1);
        $query->condition('type', $param);
        /*
          $langcode = \Drupal::languageManager()->getCurrentLanguage();
          $query->condition('langcode', $langcode);
         */
        $ids = $query->pager(10)->execute();

        if (! empty($ids)) {
            $storage = \Drupal::entityTypeManager()->getStorage('node');
            $entities = $storage->loadMultiple($ids);

            foreach ($entities as $node) {
                /* $item = [
                  '#markup' => \Drupal\Core\Link::fromTextAndUrl($node->getTitle(), \Drupal\Core\Url::fromUri('http://test.me/go/here')),
                  '#wrapper_attributes' => [
                  'class' => [
                  'wrapper__links__link',
                  ],
                  ],
                  ]; */
                $item = $node->toLink();
                $items[] = $item;
            }
        } else {
            $items[] = $this->t('Le type de contenu %node n\'exite pas!', array('%node' => $param));
        }
        
        // Voir comment contruire et interpreter la liste/template :
        // item-list.html.twig
        // https://api.drupal.org/api/drupal/core%21modules%21system%21templates%21item-list.html.twig/8.2.x
        
        $list = [
            '#theme' => 'item_list',
            '#items' => $items,
        ];
        
        // https://api.drupal.org/api/drupal/core%21includes%21pager.inc/function/pager_default_initialize/8.2.x
        $pager = [
            '#type' => 'pager',
        ];

        return [$list, $pager];
    }

}
