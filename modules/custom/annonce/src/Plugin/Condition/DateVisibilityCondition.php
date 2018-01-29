<?php

// https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Datetime!DrupalDateTime.php/class/DrupalDateTime/8.2.x
// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Datetime%21Element%21Datetime.php/class/Datetime/8.4.x
// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Condition%21ConditionPluginBase.php/function/ConditionPluginBase%3A%3AvalidateConfigurationForm/8.2.x

namespace Drupal\annonce\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Component\Datetime\DateTimePlus;

/**
 * Provides a 'Date visibility condition' condition to enable a condition based in module selected status.
 *
 * @Condition(
 *   id = "date_visibility_condition",
 *   label = @Translation("Date Visibility"),
 * )
 *
 */
class DateVisibilityCondition extends ConditionPluginBase {

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
                $configuration, $plugin_id, $plugin_definition
        );
    }

    /**
     * Creates a new DateVisibilityCondition object.
     *
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
    public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
        $ts_s = $this->configuration['date_s'] ? DrupalDateTime::createFromTimestamp($this->configuration['date_s']) : '';
        $ts_e = $this->configuration['date_e'] ? DrupalDateTime::createFromTimestamp($this->configuration['date_e']) : '';

        $form['date_s'] = [
            '#type' => 'datetime',
            '#title' => $this->t('Select a start date'),
            '#default_value' => $ts_s,
            '#date_date_element' => 'date',
            '#date_time_element' => 'none',
        ];

        $form['date_e'] = [
            '#type' => 'datetime',
            '#title' => $this->t('Select a end date'),
            '#default_value' => $ts_e,
            '#date_date_element' => 'date',
            '#date_time_element' => 'none',
        ];

        return parent::buildConfigurationForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
        $ts_s = $form_state->getValue('date_s') ? $form_state->getValue('date_s')->getTimestamp() : NULL;
        $ts_e = $form_state->getValue('date_e') ? $form_state->getValue('date_e')->getTimestamp() : NULL;

        $this->configuration['date_s'] = $ts_s;
        $this->configuration['date_e'] = $ts_e;

        parent::submitConfigurationForm($form, $form_state);
    }

    public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
        // rien si c'est ok
        // set-error-message (voir module hello) en cas d'erreur
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
        return [
            'date_s' => ['#default_value' => ''], //            /*'#default_value' => new DrupalDateTime('2000-01-01 00:00:00'),*/
            'date_e' => ['#default_value' => ''],
                ] + parent::defaultConfiguration();
    }

    /**
     * Evaluates the condition and returns TRUE or FALSE accordingly.
     *
     * @return bool
     *   TRUE if the condition has been met, FALSE otherwise.
     */
    public function evaluate() {
        /*
          • Vous traiterez les cas suivants :
          • aucune date renseignée
          • date de début uniquement.
          • date de fin uniquement.
          • date de début et date de fin.
         */

        $dateStart = $this->configuration['date_s'];
        $dateEnd = $this->configuration['date_e'];

        // Aucune date.
        if (empty($dateStart) && empty($dateEnd)) {
            return TRUE;
        }
        // Date de début uniquement.
        if (!empty($dateStart) && empty($dateEnd)) {
            if ($dateStart <= REQUEST_TIME) {
                return TRUE;
            } else
                return FALSE;
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
        } else
            return FALSE;
    }

    /**
     * Provides a human readable summary of the condition's configuration.
     */
    public function summary() {
        /*
          $module = $this->getContextValue('module');
          $modules = system_rebuild_module_data();

          $status = ($modules[$module]->status) ? t('enabled') : t('disabled');

          return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
         */
    }

}
