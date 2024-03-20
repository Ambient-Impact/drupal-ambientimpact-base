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

}
