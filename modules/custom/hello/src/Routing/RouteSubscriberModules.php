<?php

// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Routing%21RouteSubscriberBase.php/class/RouteSubscriberBase/8.2.x
// https://www.drupal.org/docs/8/api/routing-system/altering-existing-routes-and-adding-new-routes-based-on-dynamic-ones

namespace Drupal\hello\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriberModules extends RouteSubscriberBase {

    public function alterRoutes(RouteCollection $collection) {
        // Deny all access to // http://user8.d8.lab/admin/modules
        // system.modules_list est le nom de la route dans routing.yml
        
        /*
        if ($route = $collection->get('system.modules_list')) {
            $route->setRequirement('_access', 'FALSE');
        }
         */
    }

}
