<?php

// https://api.drupal.org/api/drupal/elements
// faire Type => FormElement
// https://api.drupal.org/api/drupal/namespace/Drupal!Core!Render!Element/8.2.x
// le formulire peut etre affiche avec un route dans le fichie de routing
// ou il peut etre inclus dans un block ou recuperé formBuild()
// https://api.drupal.org/api/drupal/core%21core.api.php/group/form_api/8.4.x
// $extra = '612-123-4567';
// $form = \Drupal::formBuilder()->getForm('Drupal\mymodule\Form\ExampleForm', $extra);


// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Render%21Element%21Select.php/class/Select/8.2.x
// https://api.drupal.org/api/drupal/elements/8.2.x
// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Render%21Element%21Container.php/class/Container/8.2.x
// https://www.drupal.org/docs/8/api/render-api/the-drupal-8-render-pipeline#html-main-content-renderer-pipeline
// https://www.drupal.org/docs/8/api/render-api/render-arrays
// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Render%21Element%21RenderElement.php/class/RenderElement/8.4.x
// https://api.drupal.org/api/drupal/core%21core.api.php/group/form_api/8.4.x

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConversionTable;

class ConversionTableForm extends FormBase {

    public function getFormId() {
        return 'conversion_table';
    }

    public function getTitle() {
        return t('Conversion Table');
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $conv_fr = '';
        $conv_to = '';

        $form['convert'] = array(
            '#type' => 'hidden',
            '#value' => 'qsdqkjdlkqjsdlkqjdsl',
        );
        
        $convert_opts = [];
        foreach (range(0, 8) as $opid) {
            $convert_opts[$opid] = $this->t($this->getConversionName($opid));
        }
        
        $form['conv_fr'] = [
            '#type' => 'select',
            '#options' => $convert_opts,
        ];

        $form['save'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Convert'),
        );

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        
    }

    
    private function getConversionID($id) {
        switch ($id) {
            case $this->getConversionName(0):
                return 0;
            case $this->getConversionName(1):
                return 1;
            case $this->getConversionName(2):
                return 2;
            case $this->getConversionName(3):
                return 3;
            case $this->getConversionName(4):
                return 4;
            case $this->getConversionName(5):
                return 5;
            case $this->getConversionName(6):
                return 6;
            case $this->getConversionName(7):
                return 7;
            default:
                return -1;
        }
    }
    
    private function getConversionName($id) {
        switch ($id) {
            case 0:
                return 'Grammes (g)';
            case 1:
                return 'Onces (oz)';
            case 2:
                return 'Livres (lb)';
            case 3:
                return 'Degrés Celsius (°C)';
            case 4:
                return 'Degrés Fahrenheit (°F)';
            case 5:
                return 'Millilitres (ml)';
            case 6:
                return 'Cuillère';
            case 7:
                return 'Tasse';
            default:
                return '---';
        }
    }
    
    private function getConversionMapID($id) {
        switch ($id) {
            case 0:
                return [0];
            case 1:
                return [1];
            case 2:
                return [2];
            case 3:
                return [3];
            case 4:
                return [4];
            case 5:
                return [5];
            case 6:
                return [6];
            case 7:
                return [7];
            default:
                return [];
        }        
    }
 
    private function getConversionMap($id_fr, $id_to) {
        //conversion map
        $map = [
            0 => [               //gr
                1 => 0.035274,   //gr => oz
                2 => 0.00220462, //gr => lb
            ]
        ];
        
        $this->addMapValue($id_fr, $id_to, $coef_fr_to);
        $this->addMapValue(0, 1, 0.035274);
        $this->addMapValue(0, 1, 0.00220462);
        
        if(isset($map[$id_fr][$id_to]) && !empty($map[$id_fr][$id_to])) {
            return $map[$id_fr][$id_to];
        }
        
        return NULL;
    }
    
}
