<?php

ini_set('memory_limit', 1024 * 1024 * 10);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$error = new Codin\Fault\ErrorHandler();

if ('cli' === php_sapi_name()) {
    $error->attach(new Codin\Fault\Handlers\Console());
} else {
    //$error->attach(new Codin\Fault\Handlers\JsonDump());
    //$error->attach(new Codin\Fault\Handlers\PrintDump());
    $error->attach(new Codin\Fault\Handlers\WebView($debug = true));
}

$error->register();

function triggerUserError(): void
{
    $a = [];
    $b = 0;
    echo $a[$b];
}

function triggerUserException(): void
{
    try {
        throw new RuntimeException('User exception triggered');
    } catch (Throwable $e) {
        throw new OverflowException('Another user exception triggered', 0, $e);
    }
}

function tests(): void
{
    triggerUserException();
}

tests();
