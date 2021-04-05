<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Throwable;

class PrintDump implements ExceptionHandler
{
    public function handle(Throwable $e): void
    {
        if (!\headers_sent()) {
            \header('Content-Type: text/plain', true, 500);
        }
        echo $e;
    }
}
