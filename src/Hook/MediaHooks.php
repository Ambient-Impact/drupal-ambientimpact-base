<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\ambientimpact_core\ComponentPluginManagerInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Hook\Attribute\Hook;
use function is_object;

/**
 * Media hooks.
 */
class MediaHooks implements ContainerInjectionInterface {

  use AutowireTrait;

  /**
   * Constructor; saves dependencies.
   *
   * @param \Drupal\ambientimpact_core\ComponentPluginManagerInterface $componentManager
   *   The component plug-in manager service.
   */
  public function __construct(
    protected readonly ComponentPluginManagerInterface $componentManager,
  ) {}

  /**
   * Prepares variables for image field templates.
   *
   * This sets a max-width on image field items so that no phantom link space
   * can occur.
   *
   * @see \Drupal\ambientimpact_media\Plugin\AmbientImpact\Component\Image::preprocessFieldSetImageFieldMaxWidth()
   *   This adds a max-width on each field item.
   */
  #[Hook('preprocess_field__image')]
  public function preprocessImageField(array &$variables): void {

    foreach ($variables['items'] as $delta => &$item) {

      // Set a default for constrain_width in all image field items so that we
      // don't get an PHP undefined notice when the Image component attempts to
      // read it where it isn't already explicitly set. An example of this is on
      // user profile image fields.
      if (!isset($item['content']['#constrain_width'])) {
        $item['content']['#constrain_width'] = true;
      }

    }

    $imageComponent = $this->componentManager->getComponentInstance('image');

    // If ambientimpact_media is not installed, this component will not exist so
    // calling the method below would cause a fatal error in that case.
    if (is_object($imageComponent)) {

      $imageComponent->preprocessFieldSetImageFieldMaxWidth($variables);

    }

  }

  /**
   * Prepares variables for media thumbnail field templates.
   *
   * Example of this field are remote video media when not displaying the
   * embedded video but the image preview.
   *
   * This sets the 'constrain_width' value to false so that small, low
   * resolution thumbnails take up the same space as high resolution
   * thumbnails, in line with how they're shown in actual YouTube/Vimeo
   * embedded videos.
   */
  #[Hook('preprocess_field__media__thumbnail')]
  public function preprocessMediaThumbnailField(array &$variables): void {

    foreach ($variables['items'] as $delta => &$item) {
      $item['content']['#constrain_width'] = false;
    }

  }

}
