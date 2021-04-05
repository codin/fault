<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Throwable;

class JsonDump implements ExceptionHandler
{
    public function handle(Throwable $e): void
    {
        if (!\headers_sent()) {
            \header('Content-Type: application/json', true, 500);
        }
        echo \json_encode([
            'code' => $e->getCode(),
            'title' => \get_class($e),
            'detail' => $e->getMessage(),
            'source' => (string) $e,
        ], JSON_PRETTY_PRINT);
    }
}
