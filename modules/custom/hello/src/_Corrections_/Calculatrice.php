<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements a calculator form.
 */
class HelloCalculator extends FormBase {

  protected $state;

  /**
   * {@inheritdoc}.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormID() {
    return 'hello_calculator';
  }

  public function operation($value1, $value2, $operator) {
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
    return $resultat;
  }

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
      '#type'          => 'textfield',
      '#title'         => $this->t('First value'),
      '#description'   => $this->t('Enter first value.'),
      '#required'      => TRUE,
      '#default_value' => '2',
      '#ajax'          => array(
        'callback'  => array($this, 'AjaxValidateNumeric'),
        'event'     => 'change',
      ),
      '#prefix' => '<span id="error-message-value1"></span>',
    );
    $form['value2'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Second value'),
      '#description'   => $this->t('Enter second value.'),
      '#required'      => TRUE,
      '#default_value' => '2',
      '#ajax'          => array(
        'callback'  => array($this, 'AjaxValidateNumeric'),
        'event'     => 'change',
      ),
      '#prefix' => '<span id="error-message-value2"></span>',
    );
    return $form;
  }

  public function AjaxValidateNumeric(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    // print_r(json_encode($form_state->getTriggeringElement()));

    $field = $form_state->getTriggeringElement()['#name'];
    $css = ['border' => '2px solid green'];
    $message = $this->t('OK!');
    if (!is_numeric($form_state->getValue($field))) {
      $css = ['border' => '2px solid red'];
      $message = $this->t('%field must be numeric!', array('%field' => $form[$field]['#title']));
    }

    $response->AddCommand(new CssCommand("[name=$field]", $css));
    $response->AddCommand(new HtmlCommand('#error-message-' . $field, $message));

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value_1   = $form_state->getValue('value1');
    $value_2   = $form_state->getValue('value2');
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
    $value_1   = $form_state->getValue('value1');
    $value_2   = $form_state->getValue('value2');
    $operation = $form_state->getValue('operation');
    $view      = $form_state->getValue('view');

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

    // Redirection vers la route "hello.calculator.result".
    if ($view == 'redirect') {
      // On passe le résultat en paramètre dans l'url.
      $form_state->setRedirect('hello.calculator.result', array('result' => $resultat));
    }

    // On affiche le résultat dans le formulaire.
    if ($view == 'rebuild') {
      // On passe le résultat.
      $form_state->addRebuildInfo('result', $resultat);
      // Reconstruction du formulaire avec les valeurs saisies.
      $form_state->setRebuild();
    }

  }

}
