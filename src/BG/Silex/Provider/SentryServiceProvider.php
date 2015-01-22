<?php

namespace BG\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class SentryServiceProvider
 *
 * @package BG\Silex\Provider
 */
class SentryServiceProvider implements ServiceProviderInterface
{
    public $sentryConfig = array();

    public function __construct($sentryConfig)
    {
        $this->sentryConfig = $sentryConfig;
    }

    const OPT_DSN = 'dsn';
    const OPT_SEND_ERRORS_LAST = 'send_errors_last';

    protected static $defaultOptions = array(
        self::OPT_DSN => null,
        self::OPT_SEND_ERRORS_LAST => false,
    );

    /**
     * @todo better|deeper validation needed
     *
     * @param array $sentryConfig
     *
     * @return bool
     */
    public function validateProviderConfig(array $sentryConfig)
    {
        return (array_key_exists('client', $sentryConfig)
            && array_key_exists('error_handler', $sentryConfig)
            && array_key_exists('options', $sentryConfig)
        );
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    public function register(Application $app)
    {
        $defaultOptions = self::$defaultOptions;

        /**
         * @var string $providerKey
         * @var array  $providerConfig
         */
        foreach ($this->sentryConfig['provider'] as $providerKey => $providerConfig) {

            if (!$this->validateProviderConfig($providerConfig)) {
                /** @todo throw exception instead of loop over */
                continue;
            }

            // raven client required?
            if ((bool) $providerConfig['client'] === true) {
                $app[$providerKey] = $app->share(function () use($app, $defaultOptions, $providerConfig) {
                    $options = array_merge($defaultOptions, $providerConfig['options']);
                    return new \Raven_Client($options[SentryServiceProvider::OPT_DSN], $options);
                });
            }

            // raven error handler required?
            if ((bool) $providerConfig['error_handler'] === true) {
                $app[$providerKey.'.error_handler'] = $app->share(function() use($app, $defaultOptions, $providerConfig, $providerKey) {
                    $options = array_merge($defaultOptions, $providerConfig['options']);
                    return new \Raven_ErrorHandler($app[$providerKey], $options[SentryServiceProvider::OPT_SEND_ERRORS_LAST]);
                });
            }
        }
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {}
}
