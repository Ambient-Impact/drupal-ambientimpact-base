<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Pager hooks.
 */
class PagerHooks {

  /**
   * Prepares variables for pager templates.
   */
  #[Hook('preprocess_pager')]
  public function preprocess(array &$variables): void {

    // This doesn't seem to work in \hook_element_info_alter() for some absurd
    // reason, which would have been the preferred way to attach this.
    $variables['#attached']['library'][] = 'ambientimpact_base/pager';

    // Use singleton tooltip if this is being rendered.
    if (!empty($variables['items'])) {

      $variables['attributes']['data-tooltips-singleton'] = 'true';

      $variables['#attached'][
        'library'
      ][] = 'ambientimpact_base/tooltips_singleton';

    }

  }

}
