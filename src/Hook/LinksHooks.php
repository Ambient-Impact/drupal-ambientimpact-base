<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Links list hooks.
 */
class LinksHooks {

  /**
   * Prepares variables for links templates.
   */
  #[Hook('preprocess_links')]
  public function preprocess(array &$variables): void {

    $variables['#attached']['library'][] = 'ambientimpact_base/links';

  }

}
