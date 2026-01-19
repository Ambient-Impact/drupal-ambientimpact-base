<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Table hooks.
 */
class TableHooks {

  /**
   * Implements hook_element_info_alter.
   */
  #[Hook('element_info_alter')]
  public function elementInfoAlter(array &$info): void {

    if (!isset($info['table'])) {
      return;
    }

    $info['table']['#attached']['library'][] = 'ambientimpact_base/table';

  }

}
