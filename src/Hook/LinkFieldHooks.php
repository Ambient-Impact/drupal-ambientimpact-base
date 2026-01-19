<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Link field hooks.
 */
class LinkFieldHooks {

  /**
   * Prepares variables for link field templates.
   *
   * This sets classes on link fields to indicate whether they have a link
   * title or are outputting the URL as their title. This is useful to force
   * browsers to wrap URLs so they don't break out of the layout.
   */
  #[Hook('preprocess_field__link')]
  public function preprocess(array &$variables): void {

    foreach ($variables['element']['#items'] as $i => $item) {

      if (!empty($item->title)) {
        $linkClass = 'field__item--link-has-title';
      } else {
        $linkClass = 'field__item--link-no-title';
      }

      $variables['items'][$i]['attributes']->addClass($linkClass);

    }

  }

}
