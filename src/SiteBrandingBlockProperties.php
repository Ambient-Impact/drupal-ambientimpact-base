<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Site branding block properties preprocess class.
 */
class SiteBrandingBlockProperties implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The Drupal theme handler service.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected ThemeHandlerInterface $themeHandler;

  /**
   * The Drupal theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected ThemeManagerInterface $themeManager;

  /**
   * Constructor; saves dependencies.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   *   The Drupal theme handler service.
   *
   * @param \Drupal\Core\Theme\ThemeManagerInterface $themeManager
   *   The Drupal theme manager.
   */
  public function __construct(
    TranslationInterface  $stringTranslation,
    ThemeHandlerInterface $themeHandler,
    ThemeManagerInterface $themeManager
  ) {
    $this->stringTranslation  = $stringTranslation;
    $this->themeHandler       = $themeHandler;
    $this->themeManager       = $themeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('string_translation'),
      $container->get('theme_handler'),
      $container->get('theme.manager')
    );
  }

  /**
   * Prepares variables for the 'system_branding_block' block template.
   *
   * This adds and alters the necessary variables to allow greater customization
   * of the branding block compared to what Drupal core offers. A primary
   * motivation for this is that core doesn't provide a way to define dimensions
   * or alt text for the logo in a reusable way, other than hard coding those in
   * the block template.
   *
   * Adds support for the following keys in a theme's .info.yml:
   *
   * - logo_alt: Alternative text for the logo image.
   *
   * - logo_width: The width of the logo in pixels.
   *
   * - logo_height: The height of the logo in pixels.
   *
   * - site_name: Path to an image file representing the name of the site,
   *   relative to the theme's directory.
   *
   * - site_name_alt: Alternative text for the site name image. If not provided,
   *   the site's name is used, as defined in 'Basic site settings'.
   *
   * - site_name_width: The width of the site name image in pixels.
   *
   * - site_name_height: The height of the site name image in pixels.
   *
   * - site_slogan: Path to an image file representing the slogan of the site,
   *   relative to the theme's directory.
   *
   * - site_slogan_alt: Alternative text for the site slogan image. If not
   *   provided, the site's slogan is used, as defined in 'Basic site settings'.
   *
   * - site_slogan_width: The width of the site slogan image in pixels.
   *
   * - site_slogan_height: The height of the site slogan image in pixels.
   *
   * Adds the following variables:
   *
   * - site_logo_link_attributes: An Attributes object containing attributes for
   *   the site logo link.
   *
   * - site_name_link_attributes: An Attributes object containing attributes for
   *   the site name link.
   *
   * - front_page_url: Contains a Url object pointing to the front page. This
   *   allows customization of the front page URL if it varies based on some
   *   criteria, e.g. cache contexts.
   *
   * @param array &$variables
   *   Variables from
   *     \ambientimpact_base_preprocess_block__system_branding_block().
   *
   * @todo Split this up into multiple methods to logically group functionality.
   *
   * @see \system_preprocess_block()
   *   Creates the 'site_logo', 'site_name', and 'site_slogan' variables.
   *
   * @see ../templates/block/block--system-branding-block.html.twig
   *   Altered from the Classy template to accomodate the above changes.
   *
   * @see https://www.drupal.org/project/drupal/issues/2780293
   *   Ongoing Drupal core issue to add configurable logo alt attribute.
   *
   * @see https://www.drupal.org/docs/drupal-apis/cache-api/cache-contexts
   *   Cache contexts documentation.
   *
   * @see \hook_block_build_BASE_BLOCK_ID_alter()
   *   Cacheability metadata can be added/altered in this hook if need be.
   */
  public function preprocess(array &$variables): void {

    // Create the 'front_page_url' variable if it hasn't been provided.
    if (!isset($variables['front_page_url'])) {
      $variables['front_page_url'] = Url::fromRoute('<front>');
    }

    foreach (['site_logo', 'site_name'] as $key) {
      if (!$variables['content'][$key]['#access']) {
        continue;
      }

      // Create the 'site_logo_link_attributes' and 'site_name_link_attributes'
      // variables if they don't exist yet.
      if (!isset($variables[$key . '_link_attributes'])) {
        $variables[$key . '_link_attributes'] = new Attribute();
      }

      // Set the default rel and title attributes. These are defined here rather
      // than in the Twig template so that they can be altered by sub-themes.
      $variables[$key . '_link_attributes']
        ->setAttribute('rel', 'home')
        ->setAttribute('title', $this->t('Home'));
    }

    // This contains all of the key/value pairs in the theme's .info.yml file.
    /** @var array */
    $activeThemeInfo = $this->themeHandler->listInfo()[
      $this->themeManager->getActiveTheme()->getName()
    ]->info;

    // Build the name and slogan render arrays into images if they're provided.
    foreach (['name', 'slogan'] as $brandingType) {

      if (
        empty($activeThemeInfo['site_' . $brandingType]) ||
        !$variables['content']['site_' . $brandingType]['#access']
      ) {
        continue;
      }

      $renderArray = &$variables['content']['site_' . $brandingType];

      $renderArray = [
        '#theme'  => 'image',
        '#access' => true,
      ];

      // Parse the provided theme-relative URL.
      $parsedURL = UrlHelper::parse($activeThemeInfo['site_' . $brandingType]);

      // Build the full URI to the file, preserving any query and fragment.
      $renderArray['#uri'] = Url::fromUri(
        'base:' . $this->themeManager->getActiveTheme()->getPath() . '/' .
          $parsedURL['path'],
        [
          'query'     => $parsedURL['query'],
          'fragment'  => $parsedURL['fragment'],
        ]
      )->toString();

      // Grab the width, height, and alt from the .info.yml if they exist.
      foreach (['width', 'height', 'alt'] as $propertyName) {

        $key = 'site_' . $brandingType . '_' . $propertyName;

        if (empty($activeThemeInfo[$key])) {
          continue;
        }

        $renderArray['#' . $propertyName] = $activeThemeInfo[$key];

      }

    }

    // // Attempt to set a max-width on the logo and name links.
    // foreach ([
    //   'logo'  => 'logo',
    //   'name'  => 'site_name',
    // ] as $brandingType => $infoKey) {
    //   if (empty($activeThemeInfo[$infoKey . '_width'])) {
    //     continue;
    //   }

    //   // If a 'style' attribute already exists, try to explode it so that we can
    //   // determine if there's an existing max-width.
    //   if (
    //     $variables['site_' . $brandingType . '_link_attributes']
    //       ->offsetExists('style')
    //   ) {
    //     $parsedStyleArray = explode(
    //       ';', $variables['site_' . $brandingType . '_link_attributes']
    //         ->offsetGet('style')
    //     );
    //   } else {
    //     $parsedStyleArray = [];
    //   }

    //   $keyedStyleArray = [];

    //   // Parse styles into key/value pairs.
    //   foreach ($parsedStyleArray as $delta => $value) {
    //     // Skip empty values.
    //     if (empty($value)) {
    //       continue;
    //     }

    //     $explodedValue = explode(':', $value);

    //     // Skip any values that can't be split into a property: value pair.
    //     if (count($explodedValue) !== 2) {
    //       continue;
    //     }

    //     $keyedStyleArray[trim($explodedValue[0])] = trim($explodedValue[1]);
    //   }

    //   // If no 'max-width' key exists, set it to the logo width and implode the
    //   // whole style array back into a string.
    //   if (!isset($keyedStyleArray['max-width'])) {
    //     $keyedStyleArray['max-width'] =
    //       $activeThemeInfo[$infoKey . '_width'] . 'px';

    //     $newStyle = '';

    //     foreach ($keyedStyleArray as $propertyName => $propertyValue) {
    //       $newStyle .= $propertyName . ':' . $propertyValue . ';';
    //     }

    //     $variables['site_' . $brandingType . '_link_attributes']->setAttribute(
    //       'style',
    //       $newStyle
    //     );
    //   }
    // }

    if ($variables['content']['site_logo']['#access']) {

      // Update the logo URI just in case it's been changed in some other
      // preprocess function or hook.
      $variables['content']['site_logo']['#uri'] = $variables['site_logo'];

      foreach ([
        'logo_alt'    => 'alt',
        'logo_width'  => 'width',
        'logo_height' => 'height',
      ] as $key => $propertyName) {

        if (!empty($activeThemeInfo[$key])) {
          $variables['content']['site_logo']['#' . $propertyName] =
            $activeThemeInfo[$key];
        }

      }

    }

    // Update the site name and slogan just in case they've been changed in some
    // other preprocess function or hook.
    foreach ([
      // This is always a string.
      'name'    => $variables['site_name'],
      // This is an empty string if not outputting, otherwise it's a render
      // array. Annoying? Yes.
      'slogan'  => isset($variables['site_slogan']['#markup']) ?
        $variables['site_slogan']['#markup'] : $variables['site_slogan'],
    ] as $brandingType => $brandingString) {

      if (!$variables['content']['site_' . $brandingType]['#access']) {
        continue;
      }

      // If using the default structure without an image.
      if (isset($variables['content']['site_' . $brandingType]['#markup'])) {
        $variables['content']['site_' . $brandingType]['#markup'] =
          $brandingString;

      // If using an image. Note that if alt text is already present, this won't
      // overwrite it.
      } else if (
        isset($variables['content']['site_' . $brandingType]['#theme']) &&
        empty($variables['content']['site_' . $brandingType]['#alt'])
      ) {
        $variables['content'][
          'site_' . $brandingType
        ]['#alt'] = $brandingString;
      }

    }

  }

}
