<?php

ini_set('memory_limit', 1024 * 1024 * 10);
error_reporting(-1);

require __DIR__ . '/vendor/autoload.php';

$error = new Codin\Fault\ErrorHandler;

if ('cli' === php_sapi_name()) {
    $error->attach(new Codin\Fault\Handler\ConsoleHandler());
} else {
    $error->attach(new Codin\Fault\Handler\WebHandler($debug = true));
}

$error->register();

function testNotice(): void
{
    $a = [];
    $b = 0;
    echo $a[$b];
}

function testWarning(): void
{
    $c = null;
    foreach ($c as $d) {
    }
}

function tests(): void
{
    // testNotice();
    testWarning();
}

tests();
