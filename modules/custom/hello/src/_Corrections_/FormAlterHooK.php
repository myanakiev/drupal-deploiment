<?php

function hello_form_alter(array &$form, FormStateInterface $form_state, $form_id) {

 if ($form_id == 'contact_message_feedback_form'){
   $form['phone_number'] = array(
     '#type'     => 'tel',
     '#title'    => t('Telephone'),
     '#required' => TRUE,
     // '#weight' => - 10
   );

   $form['actions']['submit']['#value'] = t('SEND NOW!');

   
   $form['name']['#weight']         = 100;
   $form['mail']['#weight']         = 200;
   $form['phone_number']['#weight'] = 300;
   $form['message']['#weight']      = 400;
   $form['actions']['#weight']      = 500;

   unset($form['#process']);

   $form['subject']['#access'] = FALSE;
 }
}