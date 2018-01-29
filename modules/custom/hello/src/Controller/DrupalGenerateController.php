<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DrupalGenerateController.
 */
class DrupalGenerateController extends ControllerBase {

  /**
   * Getcontent.
   *
   * @return string
   *   Return Hello string.
   */
  public function getContent() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: getContent')
    ];
  }

}
