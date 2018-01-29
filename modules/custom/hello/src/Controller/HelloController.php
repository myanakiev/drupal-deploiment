<?php

//http://user8.d8.lab/hello
//http://user8.d8.lab/hello/toto

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase {

    public function getTitle() {
        //https://drupal.stackexchange.com/questions/181828/how-do-i-set-the-page-title
        
        return $this->t('Mon Titre de la Page');
    }

    public function getContent($param) {
        // c'est la meme chose:
        //$this->currentUser()->getAccountName();
        //\Drupal::currentUser()->getAccountName();
        //\Drupal::service('current_user')->getAccountName();

        $user = $this->currentUser()->getAccountName();
        $vars = array('%user' => empty($user) ? 'anonymous' : $user, '%param' => $param);

        $build = [];
        $build['#markup'] = $this->t('Vous êtes sur la page Hello.<br> Votre nom d\'utilisateur est %user.<br> Votre param est %param.<br> ', $vars);
        //$build['#title'] = 'Hello Page Title';

        return $build;
    }

}
