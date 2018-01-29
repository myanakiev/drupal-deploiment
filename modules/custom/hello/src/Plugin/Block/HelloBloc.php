<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Hello block.
 *
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello Block")
 * )
 */
class HelloBloc extends BlockBase {

    public function build() {
        $time = \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_time');
        $user = \Drupal::service('current_user');
        
        $vars = array('%date' => $time, '%user' => empty($user->getAccountName()) ? 'anonymous' : $user->getAccountName());
        
        $build = [];
        $build['#markup'] = $this->t('Bienvenue %user.<br> Il est %date.', $vars);
        //$build['#title'] = 'Hello Block Title';
        $build['#cache'] = [
            'keys' => ['build_block_hello'], 
            'max-age' => 1000, 
            'contexts' => ['user'],
            // c'est meiux d'effacer le cache via cette ligne car elle
            // s'execute lorsqu'on modifie le rendu du block pour l'user avec
            // id qui a ete modifié. Contexe marche aussi mais c'est plus propre.
            'tags' => ['user:' . $user->id()],
            ];

        return $build;
    }

}
