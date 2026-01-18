<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Node hooks.
 */
class NodeHooks {

  /**
   * Implements hook_theme_suggestions_HOOK_alter.
   *
   * This adds our 'node__extended' as a template suggestion so that we can
   * extend node.html.twig without re-implementing the whole template.
   *
   * @see node--extended.html.twig
   */
  #[Hook('theme_suggestions_node_alter')]
  public function themeSuggestionsAlter(
    array &$suggestions, array $variables,
  ): void {

    $suggestions[] = 'node__extended';

  }

}
