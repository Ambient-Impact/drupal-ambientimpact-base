<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base\Hook;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\Error;

/**
 * Responsive image hooks.
 */
class ResponsiveImageHooks {

  /**
   * Our logger channel name.
   */
  protected const LOGGER_CHANNEL = 'ambientimpact_base';

  /**
   * Constructor; saves dependencies.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   The logger channel factory.
   */
  public function __construct(
    protected readonly LoggerChannelFactoryInterface $loggerChannelFactory,
  ) {}

  /**
   * Prepares variables for responsive image formatter templates.
   *
   * This prevents an \InvalidArgumentException in \Drupal\Core\Url::fromUri()
   * if the 'url' variable is a local absolute path, i.e. starts with '/'. This
   * seems to occur with our responsive-image-formatter.html.twig, and possibly
   * other edge cases. If \Drupal\Core\Url::fromUserInput() can parse it, the
   * resulting Url object will be used. If it throws an error, an empty string
   * will be used for the URL and the exception will be logged.
   *
   * @param array &$variables
   *
   * @see \template_preprocess_responsive_image_formatter()
   */
  #[Hook('preprocess_responsive_image_formatter')]
  public function preprocessFormatter(array &$variables): void {

    // Return if there's no URL, if it's not a string, or if it looks like a
    // valid external URL.
    if (
      empty($variables['url']) ||
      !\is_string($variables['url']) ||
      UrlHelper::isExternal($variables['url']) === true &&
      UrlHelper::isValid($variables['url'], true)
    ) {
      return;
    }

    try {
      $url = Url::fromUserInput($variables['url']);

    } catch (\Exception $exception) {

      /** @var \Psr\Log\LoggerInterface Our logger channel. */
      $logger = $this->loggerChannelFactory->get(self::LOGGER_CHANNEL);

      // Log the exception.
      //
      // @see \watchdog_exception()
      //   We're replicating what this function does, but using dependency
      //   injection.
      $logger->error(
        '%type: @message in %function (line %line of %file).',
        Error::decodeException($exception)
      );

      $url = '';

    }

    $variables['url'] = $url;

  }

}
