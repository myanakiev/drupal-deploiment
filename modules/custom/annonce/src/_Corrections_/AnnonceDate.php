<?php

namespace Drupal\annonce\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Provides a 'Date' condition.
*
* @Condition(
*   id = “annonce_date”,
*   label = @Translation(“Date condition”),
* )
*/
class AnnonceDate extends ConditionPluginBase implements ContainerFactoryPluginInterface {

 /**
  * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
  *   The entity storage.
  * @param array $configuration
  *   The plugin configuration, i.e. an array with configuration values keyed
  *   by configuration option name. The special key 'context' may be used to
  *   initialize the defined contexts by setting it to an array of context
  *   values keyed by context names.
  * @param string $plugin_id
  *   The plugin_id for the plugin instance.
  * @param mixed $plugin_definition
  *   The plugin implementation definition.
  */
 public function __construct(array $configuration, $plugin_id, $plugin_definition) {
   parent::__construct($configuration, $plugin_id, $plugin_definition);
 }

 /**
  * {@inheritdoc}
  */
 public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
   return new static(
     $configuration,
     $plugin_id,
     $plugin_definition
   );
 }

 /**
  * {@inheritdoc}
  */
 public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
   $form = parent::buildConfigurationForm($form, $form_state);
   $form['dateStart'] = array(
     '#title' => $this->t('Starting date'),
     '#type' => 'date',
     '#default_value' => $this->configuration['dateStart'],
   );
   $form['dateEnd'] = array(
     '#title' => $this->t('Ending date'),
     '#type' => 'date',
     '#default_value' => $this->configuration['dateEnd'],
   );

   $form['negate']['#access'] = FALSE;

   return $form;
 }

 /**
  * {@inheritdoc}
  */
 public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
   $this->configuration['dateStart'] = $form_state->getValue('dateStart');
   $this->configuration['dateEnd'] = $form_state->getValue('dateEnd');
   parent::submitConfigurationForm($form, $form_state);
 }

 /**
  * {@inheritdoc}
  */
 public function summary() {
   return $this->t('Date visibility condition.');
 }

 /**
  * {@inheritdoc}
  */
 public function evaluate() {
   $dateStart = strtotime($this->configuration['dateStart']);
   $dateEnd = strtotime($this->configuration['dateEnd']);

   // Aucune date.
   if (empty($dateStart) && empty($dateEnd)) {
     return TRUE;
   }
   // Date de début uniquement.
   if (!empty($dateStart) && empty($dateEnd)) {
     if ($dateStart <= REQUEST_TIME) {
       return TRUE;
     }
     else return FALSE;
   }
   // Date de fin uniquement.
   if (empty($dateStart) && !empty($dateEnd)) {
     if ($dateEnd >= REQUEST_TIME) {
       return TRUE;
     }
   }
   // Date de début et de fin.
   if (!empty($dateStart) && !empty($dateEnd)) {
     if ($dateStart <= REQUEST_TIME && $dateEnd >= REQUEST_TIME) {
       return TRUE;
     }
   }

   else return FALSE;
 }

 /**
  * {@inheritdoc}
  */
 public function defaultConfiguration() {
   return array('dateStart' => array(), 'dateEnd' => array()) + parent::defaultConfiguration();
 }

}