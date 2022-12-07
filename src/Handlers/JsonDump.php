<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Throwable;

class JsonDump implements ExceptionHandler
{
    public function handle(Throwable $e): void
    {
        $stack = [
            $this->export($e),
        ];

        while ($e = $e->getPrevious()) {
            $stack[] = $this->export($e);
        }

        if (!headers_sent()) {
            header('Content-Type: application/json', true, 500);
        }
        echo json_encode($stack, JSON_PRETTY_PRINT);
    }

    protected function export(Throwable $e): array
    {
        return [
            'code' => $e->getCode(),
            'title' => \get_class($e),
            'detail' => $e->getMessage(),
            'source' => $e->getFile().':'.$e->getLine(),
        ];
    }
}
