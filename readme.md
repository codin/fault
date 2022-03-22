# Fault

![version](https://img.shields.io/github/v/tag/codin/fault?color=light-green&label=version&style=flat-square)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/codin/fault/Composer?label=build&logo=github&style=flat-square)](https://github.com/codin/fault/actions)

Simple error exception handler

Usage

```php
$error = new Codin\Fault\ErrorHandler();

if ('cli' === php_sapi_name()) {
    $error->attach(new Codin\Fault\Handler\ConsoleHandler());
} else {
    $error->attach(new Codin\Fault\Handler\WebHandler($debug = true));
}

$error->register();
```

Example with monolog+sentry

```php
$options = [
    'dsn' => '...',
    'environment' => '...',
];
$builder = new Sentry\ClientBuilder(new Sentry\Options($options));
$sentry = new Sentry\State\Hub($builder->getClient());

$logger = new Logger('app');
$logger->pushHandler(new Sentry\Monolog\Handler($sentry, Logger::NOTICE));

$error = new Codin\Fault\ErrorHandler();
$error->attach(new Codin\Fault\Handler\PsrHandler($logger));
$error->register();
```
