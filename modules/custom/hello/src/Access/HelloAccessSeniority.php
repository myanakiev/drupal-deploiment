<?php

// http://user8.d8.lab/hello-access/
// https://api.drupal.org/api/drupal/core%21core.services.yml/8.2.x

namespace Drupal\hello\Access;

use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;

class HelloAccessSeniority implements AccessCheckInterface {

    public function applies(Route $route) {
        return NULL;
    }

    public function access(Route $route, Request $request = NULL, AccountInterface $account) {
        $param = $route->getRequirement('_access_seniority');
        $param = is_numeric($param) ? $param : 48; // nombre d'heures that should be set in hello.routing.yml _access_seniority: '48'
        
        if($account->isAnonymous()) {
            return AccessResult::forbidden();
        }
        
        // check if user is created > 48 hours
        if(\Drupal::time()->getCurrentTime() - $account->getAccount()->created > $param * 3600) {
            // result cached per user
            // result expires every 60 seconds
            return AccessResult::allowed()->cachePerUser()->setCacheMaxAge(60);
        }
        
        return AccessResult::forbidden();
        
        //• addCacheableDependency()
    }
}
