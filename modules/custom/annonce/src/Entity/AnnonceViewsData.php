<?php

// https://api.drupal.org/api/drupal/core%21modules%21views%21src%21EntityViewsData.php/function/EntityViewsData%3A%3AgetViewsData/8.2.x
// https://api.drupal.org/api/drupal/core!modules!views!views.api.php/function/hook_views_data/8.2.x

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

    /**
     * {@inheritdoc}
     */
    public function getViewsData() {
        $data = parent::getViewsData();

        // Additional information for Views integration, such as table joins, can be
        // put here.

        $this->getViewsDataAnnonceHistory($data);
        //kint($data);

        return $data;
    }

    private function getViewsDataAnnonceHistory(&$data) {
        $base_table = 'annonce_history';

        // Setup base information of the views data.
        $data[$base_table]['table']['group'] = t('Annonce History');
        $data[$base_table]['table']['provider'] = $this->entityType->getProvider();
        $data[$base_table]['table']['base'] = [
            'field' => 'ahid',
            'title' => t('Annonce History'),
            'help' => t('Annonce history contains historical datas and can be related to annonces.'),
            'weight' => -100,
        ];

        $data[$base_table]['uid'] = array(
            'title' => t('Annonce User ID'),
            'help' => t('Annonce User ID.'),
            'field' => array('id' => 'numeric'),
            'sort' => array('id' => 'standard'),
            'filter' => array('id' => 'numeric'),
            'argument' => array('id' => 'numeric'),
            'relationship' => array(
                'base' => 'users_field_data',
                'base field' => 'uid',
                'id' => 'standard',
                'label' => t('Annonce history UID -> User ID'),
            ),
        );

        $data[$base_table]['date'] = array(
            'title' => t('Annonce View Date'),
            'help' => t('The date the annonce was viewed.'),
            'field' => array('id' => 'date'),
            'sort' => array('id' => 'date'),
            'filter' => array('id' => 'date'),
        );

        $data[$base_table]['aid'] = array(
            'title' => t('Annonce ID'),
            'help' => t('Annonce Content ID.'),
            'field' => array('id' => 'numeric'),
            'sort' => array('id' => 'standard'),
            'filter' => array('id' => 'numeric'),
            'argument' => array('id' => 'numeric'),
            'relationship' => array(
                'base' => 'annonce_field_data',
                'base field' => 'id',
                'id' => 'standard',
                'label' => t('Annonce history AID -> Annonce ID'),
            ),
        );

        $data[$base_table]['acl'] = array(
            'title' => t('Annonce Access Code'),
            'help' => t('Annonce Access Code.'),
            'field' => array(
                'id' => 'standard',
            ),
            'sort' => array(
                'id' => 'standard',
            ),
            'filter' => array(
                'id' => 'string',
            ),
            'argument' => array(
                'id' => 'string',
            ),
        );
    }

}
