<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Feed icon hooks.
 */
class FeedIconHooks {

  /**
   * Prepares variables for feed icon templates.
   *
   * Attaches the 'ambientimpact_base/feed_icon' library,
   */
  #[Hook('preprocess_feed_icon')]
  public function preprocess(array &$variables): void {

    $variables['#attached']['library'][] = 'ambientimpact_base/feed_icon';

  }

}
