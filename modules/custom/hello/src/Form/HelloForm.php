<?php

// https://api.drupal.org/api/drupal/elements
// faire Type => FormElement
// https://api.drupal.org/api/drupal/namespace/Drupal!Core!Render!Element/8.2.x
// le formulire peut etre affiche avec un route dans le fichie de routing
// ou il peut etre inclus dans un block ou recuperé formBuild()
// https://api.drupal.org/api/drupal/core%21core.api.php/group/form_api/8.4.x
// $extra = '612-123-4567';
// $form = \Drupal::formBuilder()->getForm('Drupal\mymodule\Form\ExampleForm', $extra);

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements a Hello Form
 */
class HelloForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'hello_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {        
        $form['op_1'] = array(
            '#type' => 'number',
            '#title' => $this->t('First value'),
            '#description' => $this->t('Enter first value'),
            '#size' => 40,
            '#maxlength' => 128,
            '#required' => TRUE,
                /* '#ajax' => array(
                  'callback' => array($this, 'validateTextAJAX'),
                  'event' => 'change',
                  ),
                  '#suffix' => '<span></span>',
                 */
        );
        $form['op_2'] = array(
            '#type' => 'number',
            '#title' => $this->t('Second value'),
            '#description' => $this->t('Enter second value'),
            '#size' => 40,
            '#maxlength' => 128,
            '#required' => TRUE,
        );

        $form['op_o'] = array(
            '#type' => 'radios',
            '#title' => $this->t('Operation'),
            '#options' => array(0 => $this->t('+'), 1 => $this->t('-'), 2 => $this->t('*'), 3 => $this->t('/')),
            '#default_value' => 0,
        );

        $form['result'] = array(
            '#type' => 'label',
            '#title' => !isset($form_state->getRebuildInfo()['result']) ? $this->t('Result: ?') : $this->t('Result: %res', array('%res' => $form_state->getRebuildInfo()['result'])),
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Calculate'),
            '#ajax' => array(
                'callback' => array($this, 'ex_form_ajax_handler'),
                // the ID of the parent form container to replace the form on ajax request/validate/result
                // 'wrapper' => $form['#attributes']['class'],
                //'wrapper' => 'block-bartik-content'
                
            ),
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $op_1 = $form_state->getValue('op_1');
        $op_2 = $form_state->getValue('op_2');
        $op_o = $form_state->getValue('op_o');

        if (!is_numeric($op_1)) {
            $form_state->setErrorByName('op_1', $this->t('Value must be numeric.'));
        }
        if (!is_numeric($op_2)) {
            $form_state->setErrorByName('op_2', $this->t('Value must be numeric.'));
        }
        if ($op_2 == 0 && $op_o == 3) { // devision by 0
            $form_state->setErrorByName('op_2', $this->t('Devision by 0.'));
            $form_state->setErrorByName('op_o', $this->t('Devision by 0.'));
        }

        unset($form['result']);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $op_1 = $form_state->getValue('op_1');
        $op_2 = $form_state->getValue('op_2');
        $op_o = $form_state->getValue('op_o');
        $resu = $this->t('Vide');

        switch ($op_o) {
            case 0:
                $resu = $op_1 + $op_2;
                break;
            case 1:
                $resu = $op_1 - $op_2;
                break;
            case 2:
                $resu = $op_1 * $op_2;
                break;
            case 3:
                $resu = $op_1 / $op_2;
                break;
            default:
                $resu = $this->t('Error');
                break;
        }

        // j'ajoute une autre information pour le champ resultat car apres le submit
        // j'ai un rebuild qui va regenerer le formulaire et je peux recuperer cette valeur
        // On passe le résultat.
        $form_state->addRebuildInfo('result', $resu);
        // Reconstruction du formulaire avec les valeurs saisies.
        $form_state->setRebuild();
    }

    public function validateTextAJAX(array &$form, FormStateInterface $form_state) {
        // https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Ajax%21AjaxResponse.php/class/AjaxResponse/8.2.x
        // https://api.drupal.org/api/drupal/core%21core.api.php/group/ajax/8.2.x

        $css = ['border' => '2px solid green'];
        $htm = $this->t('Ajax message... ') . $form_state->getValue('op_1');

        $res = new AjaxResponse();
        $res->addCommand(new CssCommand('.form-item-op-1 > input', $css));
        $res->addCommand(new HtmlCommand('.form-item-op-1 + span', $htm));

        return $res;
    }

    function ex_form_ajax_handler(array &$form, FormStateInterface $form_state) {
        //return $form;

        $res = new AjaxResponse();
        $res->addCommand(new HtmlCommand('#block-bartik-content', $form));
        
        //utiliser $form['#attributes']['class'] à la place de '#block-bartik-content'

        return $res;

        //return $form;
    }

}
