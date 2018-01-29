<?php

namespace Drupal\annonce;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Annonce entity.
 *
 * @see \Drupal\annonce\Entity\Annonce.
 */
class AnnonceAccessControlHandler extends EntityAccessControlHandler {

    /**
     * {@inheritdoc}
     */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
        /** @var \Drupal\annonce\Entity\AnnonceInterface $entity */
        switch ($operation) {
            case 'view':
                if (!$entity->isPublished()) {
                    return AccessResult::allowedIfHasPermission($account, 'view unpublished annonce entities');
                }
                return AccessResult::allowedIfHasPermission($account, 'view published annonce entities');

            case 'update':
                //return AccessResult::allowedIfHasPermission($account, 'edit annonce entities');
                return AccessResult::allowedIf($account->id() && $account->id() == $entity->getOwnerId() && $account->hasPermission('edit annonce entities'))->cachePerPermissions()->cachePerUser()->addCacheableDependency($entity);

            case 'delete':
                // https://api.drupal.org/api/drupal/core%21modules%21comment%21src%21CommentAccessControlHandler.php/class/CommentAccessControlHandler/8.4.x
                // voir 'edit own comments'
                // https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Access%21AccessResult.php/class/AccessResult/8.2.x
                // 
                //return AccessResult::allowedIfHasPermission($account, 'delete annonce entities');
                //return AccessResult::allowedIfHasPermissions($account, ['delete annonce entities', 'delete annonce entities author'], 'OR');

                return AccessResult::allowedIf($account->id() && $account->id() == $entity->getOwnerId() && $account->hasPermission('delete annonce entities'))->cachePerPermissions()->cachePerUser()->addCacheableDependency($entity);
        }

        // Unknown operation, no opinion.
        return AccessResult::neutral();
    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
        return AccessResult::allowedIfHasPermission($account, 'add annonce entities');
    }

}
