<?php

// http://user8.d8.lab/admin/appearance/color-config

namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;

/**
 * Implements a Hello Form
 */
class HelloColorConfigForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'hello_color_config_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames() {
        return ['hello.config'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $color = $this->config($this->getEditableConfigNames()[0])->get('color');

        $form = [];
        $form['select'] = array(
            '#type' => 'select',
            '#title' => $this->t('Couleur des blocs'),
            '#options' => array(0 => $this->getColorName(0, true), 1 => $this->getColorName(1, true), 2 => $this->getColorName(2, true), 3 => $this->getColorName(3, true)),
            '#default_value' => $this->getColorID($color),
            // add this code to make ajax form + submitFormAjaxHandler
            /*'#ajax' => array(
                'callback' => array($this, 'submitFormAjaxHandler'),
                'event' => 'change',
            ),
             */
            // add this code to make ajax form + submitFormAjaxHandler
        );

        // make return $form sans parent to remove the submit button
        // return $form;
        // if not an ajax callback
        // make the submit button
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $color = $this->getColorName($form_state->getValue('select'), false);
        $this->config($this->getEditableConfigNames()[0])->set('color', $color)->save();

        // sur save on vide le chache des blocks
        \Drupal::entityTypeManager()->getViewBuilder('block')->resetCache();

        parent::submitForm($form, $form_state);
    }

    private function getColorName($id, $translate) {
        switch ($id) {
            case 0:
                $resu = $translate ? $this->t('red') : 'red';
                break;
            case 1:
                $resu = $translate ? $this->t('green') : 'green';
                break;
            case 2:
                $resu = $translate ? $this->t('blue') : 'blue';
                break;
            case 3:
                $resu = $translate ? $this->t('orange') : 'orange';
                break;
            default:
                $resu = $translate ? $this->t('gray') : 'gray';
                break;
        }

        return $resu;
    }

    private function getColorID($name) {
        switch ($name) {
            case $this->getColorName(0, false):
                $resu = 0;
                break;
            case $this->getColorName(1, false):
                $resu = 1;
                break;
            case $this->getColorName(2, false):
                $resu = 2;
                break;
            case $this->getColorName(3, false):
                $resu = 3;
                break;
            default:
                $resu = 4;
                break;
        }

        return $resu;
    }

    function submitFormAjaxHandler(array &$form, FormStateInterface $form_state) {
        //$ajax_response->addCommand(new InvokeCommand(NULL, "$('#myForm').trigger", ['submit']));
        // on peut utiliser "submit", [] à la place du "trigger", ['submit']
        //$ajax_response->addCommand(new InvokeCommand('#hello-color-config-form', "submit", []));

        $ajax_response = new AjaxResponse();
        $ajax_response->addCommand(new InvokeCommand('#hello-color-config-form', "trigger", ['submit']));

        return $ajax_response;
    }

}
