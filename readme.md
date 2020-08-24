# Fault

Simple error exception handler

```php
$error = new Codin\Fault\ErrorHandler();

if ('cli' === php_sapi_name()) {
    $error->attach(new Codin\Fault\Handler\ConsoleHandler());
} else {
    $error->attach(new Codin\Fault\Handler\WebHandler($debug = true));
}

$error->register();
```
