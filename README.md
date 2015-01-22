# Just Another Silex Sentry Provider

[Sentry client](https://github.com/getsentry/raven-php) service provider for the [Silex](http://silex.sensiolabs.org/) framwork,
based on Vitaliy Chesnokov's [Silex Sentry Provider](https://github.com/moriony/silex-sentry-provider).

## Install via composer

Add in your ```composer.json``` the require entry for this library.
```json
{
    "require": {
        "bitgrave/silex-sentry-provider": "1.0.5"
    }
}
```
and run ```composer install``` (or ```update```) to download all files.

If you don't need development libraries, use ```composer install --no-dev``` or ```composer update --no-dev```

## Usage

### Service registration
```php

// multiple provider configuration
$sentryProviderOptions = array(
    'provider' => array(
        'sentry_1' => array(
            'client' => true,
            'error_handler' => false,
            'options' => array(
                'dsn' => 'http://public:secret@example.com/1',
                'curl_method' => 'exec'
                // ... and other sentry options
            )
        ),
        'sentry_2' => array(
            'client' => true,
            'error_handler' => false,
            'options' => array(
                'dsn' => 'http://public:secret@example.com/2',
                'curl_method' => 'exec'
                // ... and other sentry options
            )
        ),
        'sentry_3' => array(
            'client' => true,
            'error_handler' => false,
            'options' => array(
                'dsn' => 'http://public:secret@example.com/3',
                'curl_method' => 'exec'
                // ... and other sentry options
            )
        ),
        'sentry_4' => array(
            'client' => true,
            'error_handler' => true,
            'options' => array(
                'dsn' => 'http://public:secret@example.com/4',
                'curl_method' => 'async'
                // ... and other sentry options
            )
        )
    )
);

$app->register(new BG\Silex\Provider\SentryServiceProvider, $sentryProviderOptions);
```

Here you can find [other sentry options](https://github.com/getsentry/raven-php#configuration).

###  Exception capturing
```php
$app->error(function (\Exception $e, $code) use($app) {
    // ...
    $client = $app['sentry_1'];
    $client->captureException($e);
    // ...
});
```

### Error handler registration
Yoc can install error handlers and shutdown function to catch fatal errors
```php
// ...
$errorHandler = $app['sentry_1.error_handler'];
$errorHandler->registerExceptionHandler();
$errorHandler->registerErrorHandler();
$errorHandler->registerShutdownFunction();
// ...
```

## Resources
* [Silex error handlers docs](http://silex.sensiolabs.org/doc/usage.html#error-handlers)
* [Raven-php code and docs](https://github.com/getsentry/raven-php)
