<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Social links hooks.
 */
class SocialLinksHooks {

  /**
   * Prepares variables for social links templates.
   */
  #[Hook('preprocess_ambientimpact_block_social_links')]
  public function preprocess(array &$variables): void {

    // If there's more than one network, mark this as a tooltip singleton and
    // attach the library. This also prevents the generalized tooltip behaviour
    // touching it.
    if (count($variables['networks']) > 1) {

      $variables['attributes']['data-tooltips-singleton'] = 'true';

      $variables['#attached'][
        'library'
      ][] = 'ambientimpact_base/tooltips_singleton';

    }

  }

}
