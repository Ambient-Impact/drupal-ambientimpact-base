<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base;

use Drupal\ambientimpact_core\ComponentPluginManagerInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Prepares variables for details element templates.
 */
class DetailsPreprocess implements ContainerInjectionInterface {

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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.ambientimpact_component'),
    );
  }

  /**
   * Prepares variables for details element templates.
   *
   * @param array &$variables
   */
  public function preprocess(array &$variables): void {

    $this->iconifySummary($variables);

    $this->addCacheMetadata($variables);

  }

  /**
   * Wrap the <summary> element contents in an icon.
   *
   * This wraps the summary element text in an icon and adds a class to the
   * details element indicating an icon is present for styling.
   *
   * @param array &$variables
   */
  protected function iconifySummary(array &$variables): void {

    // Don't iconify the unstyled demo as that's supposed to show the browser
    // default styling.
    if (
      isset($variables['attributes']['class']) &&
      \in_array('details--demo-unstyled', $variables['attributes']['class'])
    ) {
      return;
    }

    $icon = [
      '#type'   => 'ambientimpact_icon',
      '#icon'   => 'arrow-down',
      '#bundle' => 'core',
    ];

    // This is the one that's actually output.
    $variables['title'] = $icon + [
      '#text' => $variables['title'],
    ];

    // Wrap the title under 'element' for the sake of consistency.
    $variables['element']['#title'] = $icon + [
      '#text' => $variables['element']['#title'],
    ];

    $variables['attributes']['class'][] = 'details--has-icon';

    // Note that the detailsfilter module only attaches the top-level #attached
    // and attaching this farther into the tree doesn't seem to bubble it up
    // annoyingly.
    $variables['#attached']['library'][] = 'ambientimpact_base/details.icon';

  }

  /**
   * Add cache metadata to the details element.
   *
   * Adds additional cache metadata to the details render array from the
   * component plug-in manager to make it easier to invalidate rendered content
   * containing said element.
   *
   * @param array &$variables
   */
  protected function addCacheMetadata(array &$variables): void {

    /** @var \Drupal\Core\Cache\CacheableMetadata */
    $detailsMetadata = CacheableMetadata::createFromRenderArray($variables);

    /** @var \Drupal\Core\Cache\CacheableMetadata */
    $componentMetadata = CacheableMetadata::createFromObject(
      $this->componentManager,
    );

    $detailsMetadata->merge($componentMetadata)->applyTo($variables);

  }

}
