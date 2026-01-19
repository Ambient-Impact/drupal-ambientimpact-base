<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * <html> element hooks.
 */
class HtmlHooks {

  /**
   * Prepares variables for HTML document templates.
   *
   * This adds @code <meta name="color-scheme" content="dark light"> @endcode
   * for browsers that support it, to indicate that we want to opt in to them
   * using dark mode user agent styles (e.g. for the page background and form
   * elements) when @code @media (prefers-color-scheme: dark) @endcode
   * applies.
   *
   * Default template: html.html.twig.
   *
   * @param array $variables
   *   An associative array containing:
   *   - page: A render element representing the page.
   *
   * @see https://ambientimpact.com/web/snippets/the-color-scheme-meta-tag
   *
   * @see https://caniuse.com/mdn-html_elements_meta_name_color-scheme
   */
  #[Hook('preprocess_html')]
  public function preprocess(array &$variables): void {

    $variables['page']['#attached']['html_head'][] = [
      [
        '#tag'        => 'meta',
        '#attributes' => [
          'name'    => 'color-scheme',
          'content' => 'dark light',
        ],
      ],
      'color-scheme',
    ];

  }

}
