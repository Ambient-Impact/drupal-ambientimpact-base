<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use function array_unique;
use function strtr;

/**
 * Views hooks.
 */
class ViewsHooks {

  /**
   * Alter views_view suggestions.
   *
   * Core does not currently provide any suggestions for views_view templates
   * and their preprocess, so this accomplishes that.
   */
  #[Hook('theme_suggestions_views_view_alter')]
  public function themeSuggestionsViewsViewAlter(
    array &$suggestions, array $variables,
  ): void {

    $id = $variables['view']->id();

    // Sanitize the display ID in the same way core does for entities, view
    // modes, etc.
    $displayId = strtr($variables['view']->current_display, '.', '_');

    $displayHandlerId = $variables['view']->display_handler->getPluginId();

    $suggestions[] = 'views_view__' . $id;
    $suggestions[] = 'views_view__' . $displayId;
    $suggestions[] = 'views_view__' . $displayHandlerId;
    $suggestions[] = 'views_view__' . $id . '__' . $displayId;
    $suggestions[] = 'views_view__' . $id . '__' . $displayHandlerId;

    $suggestions = array_unique($suggestions);

  }

}
