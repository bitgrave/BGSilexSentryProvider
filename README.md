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
$app->register(new BG\Silex\Provider\SentryServiceProvider, array(
    'sentry.options' => array(
        'dsn' => 'http://public:secret@example.com/1',
        // ... and other sentry options
    )
));
```

Here you can find [other sentry options](https://github.com/getsentry/raven-php#configuration).

###  Exception capturing
```php
$app->error(function (\Exception $e, $code) use($app) {
    // ...
    $client = $app['sentry'];
    $client->captureException($e);
    // ...
});
```

### Error handler registration
Yoc can install error handlers and shutdown function to catch fatal errors
```php
// ...
$errorHandler = $app['sentry.error_handler'];
$errorHandler->registerExceptionHandler();
$errorHandler->registerErrorHandler();
$errorHandler->registerShutdownFunction();
// ...
```

## Resources
* [Silex error handlers docs](http://silex.sensiolabs.org/doc/usage.html#error-handlers)
* [Raven-php code and docs](https://github.com/getsentry/raven-php)
