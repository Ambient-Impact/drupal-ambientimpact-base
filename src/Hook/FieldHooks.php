<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\ambientimpact_base\Hook\MediaHooks;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Field hooks.
 */
class FieldHooks implements ContainerInjectionInterface {

  use AutowireTrait;

  /**
   * Constructor; saves dependencies.
   *
   * @param \Drupal\Core\DependencyInjection\ClassResolverInterface $classResolver
   *   The class resolver service.
   */
  public function __construct(
    protected readonly ClassResolverInterface $classResolver,
  ) {}

  /**
   * Preprocess variables for field templates.
   *
   * This works around a problem with the image_caption formatter that
   * ambientimpact_media forces on all image fields where
   * 'preprocess_field__image' isn't called for those fields.
    *
   * @see
   * \Drupal\ambientimpact_media\Plugin\Field\FieldFormatter\ImageFormatter::viewElements()
   *
   * @todo Remove when the issue is fixed either in the base theme or in one of
   *   the modules.
   */
  #[Hook('preprocess_field')]
  public function preprocessField(array &$variables): void {

    if (
      $variables['field_type'] === 'image' &&
      $variables['element']['#formatter'] === 'image_caption'
    ) {

      $mediaHooks = $this->classResolver->getInstanceFromDefinition(
        MediaHooks::class,
      );

      $mediaHooks->preprocessImageField($variables);

    }

  }

}
