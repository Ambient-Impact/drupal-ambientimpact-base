<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use function array_values;
use function gettype;

/**
 * Menu hooks.
 */
class MenuHooks {

  /**
   * Prepares variables for single local action link templates.
   *
   * Default template: menu-local-action.html.twig.
   *
   * This removes the default 'button' and 'button-action' classes from links
   * (to remove any default button styles) and adds our own BEM classes. This
   * is far easier to do in this function than in the Twig template, where it
   * should ideally be.
   *
   * Additionally, this wraps the link text in an icon.
   *
   * @param array $variables
   *   An associative array containing:
   *   - element: A render element containing:
   *     - #link: A menu link array with 'title', 'url', and (optionally)
   *       'localized_options' keys.
   *
   * @see template_preprocess_menu_local_action()
   *   Sets the default classes.
   */
  #[Hook('preprocess_menu_local_action')]
  public function preprocessLocalAction(array &$variables): void {

    $baseClass = 'action-links';

    $linkAttributes = &$variables['link']['#options']['attributes'];

    // Remove the 'button' and 'button-action' classes but leave any others
    // intact.
    foreach ($linkAttributes['class'] as $key => $class) {

      if (
        $class !== 'button' &&
        $class !== 'button-action'
      ) {
        continue;
      }

      unset($linkAttributes['class'][$key]);

    }

    // Re-number the array indices so they're sequential again as unsetting
    // doesn't remove the index.
    $linkAttributes['class'] = array_values($linkAttributes['class']);

    $linkAttributes['class'][] = $baseClass . '__link';

    $variables['attributes']['class'][] = $baseClass . '__item';

    // If the link text is a string (and not a render array), wrap it in an
    // icon. The check is to try and not conflict with modules that might have
    // altered the link in ways we're not expecting.
    if (gettype($variables['link']['#title']) === 'string') {

      $variables['link']['#title'] = [
        '#type'     => 'ambientimpact_icon',
        '#icon'     => 'plus',
        '#bundle'   => 'core',
        '#text'     => $variables['link']['#title'],
        '#containerAttributes'  => [
          'class'     => [$baseClass . '__icon'],
        ],
      ];

    }

  }

  /**
   * Prepares variables for local tasks templates.
   *
   * Default template: menu-local-tasks.html.twig.
   */
  #[Hook('preprocess_menu_local_tasks')]
  public function preprocessLocalTasks(array &$variables): void {

    $variables['#attached']['library'][] = 'ambientimpact_base/local_tasks';

  }

}
