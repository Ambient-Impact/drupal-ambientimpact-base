<?php

declare(strict_types=1);

namespace Drupal\ambientimpact_base;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\Error;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Responsive image formatter preprocess class.
 */
class ResponsiveImageFormatterPreprocess implements ContainerInjectionInterface {

  /**
   * Our logger channel name.
   */
  protected const LOGGER_CHANNEL = 'ambientimpact_base';

  /**
   * Our logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $loggerChannel;

  /**
   * Constructor; saves dependencies.
   *
   * @param \Psr\Log\LoggerInterface $loggerChannel
   *   Our logger channel.
   */
  public function __construct(LoggerInterface $loggerChannel) {
    $this->loggerChannel = $loggerChannel;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory')->get(self::LOGGER_CHANNEL)
    );
  }

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
   *   Variables from
   *     \ambientimpact_base_preprocess_responsive_image_formatter().
   *
   * @see \template_preprocess_responsive_image_formatter()
   */
  public function preprocess(array &$variables): void {

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

      // Log the exception.
      //
      // @see \watchdog_exception()
      //   We're replicating what this function does, but using the injected
      //   logger channel.
      $this->loggerChannel->error(
        '%type: @message in %function (line %line of %file).',
        Error::decodeException($exception)
      );

      $url = '';

    }

    $variables['url'] = $url;

  }

}
