<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements a calculator form.
 */
class HelloCalculator extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // Champ destiné à afficher le résultat du calcul.
        if (isset($form_state->getRebuildInfo()['result'])) {
            $form['result'] = array(
                '#markup' => '<h2>' . $this->t('Result: ') . $form_state->getRebuildInfo()['result'] . '</h2>',
            );
        }

        $form['value1'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('First value'),
            '#description' => $this->t('Enter first value.'),
            '#required' => TRUE,
            '#default_value' => '2',
            '#ajax' => array(
                'callback' => array($this, 'AjaxValidateNumeric'),
                'event' => 'change',
            ),
            '#prefix' => '<span id="error-message-value1"></span>',
        );
        $form['operation'] = array(
            '#type' => 'radios',
            '#title' => $this->t('Operation'),
            '#description' => $this->t('Choose operation for processing.'),
            '#options' => array(
                'addition' => $this->t('Add'),
                'soustraction' => $this->t('Soustract'),
                'multiplication' => $this->t('Multiply'),
                'division' => $this->t('Divide'),
            ),
            '#default_value' => 'addition',
        );
        $form['value2'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Second value'),
            '#description' => $this->t('Enter second value.'),
            '#required' => TRUE,
            '#default_value' => '2',
            '#ajax' => array(
                'callback' => array($this, 'AjaxValidateNumeric'),
                'event' => 'change',
            ),
            '#prefix' => '<span id="error-message-value2"></span>',
        );
        $form['view'] = array(
            '#type' => 'select',
            '#title' => $this->t('View result on...'),
            '#description' => $this->t('Choose operation for processing.'),
            '#options' => array(
                'redirect' => $this->t('...a redirect page'),
                'rebuild' => $this->t('...form'),
            ),
            '#default_value' => 'redirect',
        );
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Calculate'),
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $value_1 = $form_state->getValue('value1');
        $value_2 = $form_state->getValue('value2');
        $operation = $form_state->getValue('operation');
        if (!is_numeric($value_1)) {
            $form_state->setErrorByName('value1', $this->t('First value must be numeric!'));
        }
        if (!is_numeric($value_2)) {
            $form_state->setErrorByName('value2', $this->t('Second value must be numeric!'));
        }
        if ($value_2 == '0' && $operation == 'division') {
            $form_state->setErrorByName('value2', $this->t('Cannot divide by zero!'));
        }

        unset($form['result']);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Récupère la valeur des champs.
        $value_1 = $form_state->getValue('value1');
        $value_2 = $form_state->getValue('value2');
        $operation = $form_state->getValue('operation');
        $view = $form_state->getValue('view');

        $resultat = '';
        switch ($operation) {
            case 'addition':
                $resultat = $value_1 + $value_2;
                break;
            case 'soustraction':
                $resultat = $value_1 - $value_2;
                break;
            case 'multiplication':
                $resultat = $value_1 * $value_2;
                break;
            case 'division':
                $resultat = $value_1 / $value_2;
                break;
        }

        // On passe le résultat.
        $form_state->addRebuildInfo('result', $resultat);
        // Reconstruction du formulaire avec les valeurs saisies.
        $form_state->setRebuild();
    }

}
