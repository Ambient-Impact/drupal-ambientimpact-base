<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Form element hooks.
 */
class FormElementHooks {

  /**
   * Implements hook_theme_suggestions_HOOK_alter.
   *
   * This adds our 'input__button_element' as a template suggestion if an input
   * element has both core's '#is_button' variable and a custom
   * '#use_button_element' variable and both are set to true.
   *
   * @see input--button-element.html.twig
   *   Our custom template that outputs a <button> element rather than an
   *   <input>. Also documents rationale and links.
   */
  #[Hook('theme_suggestions_input_alter')]
  public function themeSuggestionsInputAlter(
    array &$suggestions, array $variables,
  ): void {

    if (
      isset($variables['element']['#is_button']) &&
      $variables['element']['#is_button'] === true &&
      isset($variables['element']['#use_button_element']) &&
      $variables['element']['#use_button_element'] === true
    ) {
      $suggestions[] = 'input__button_element';
    }

  }

}
