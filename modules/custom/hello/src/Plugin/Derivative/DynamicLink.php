<?php

namespace Drupal\hello\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

class DynamicLink extends DeriverBase {

    public function getDerivativeDefinitions($base_plugin_definition) {
        $this->derivatives['hello.mon_lien'] = $base_plugin_definition;

        $this->derivatives['hello.mon_lien'] = [
            'title' => t('Mon Link Dyn'),
            'route_name' => 'hello.hello',
            'menu_name' => 'main',
            'weight' => 101,
        ];
        
        return $this->derivatives;
    }

}
