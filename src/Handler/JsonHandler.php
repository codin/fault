<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Codin\Fault\Frame;
use Codin\Fault\Traits;
use Throwable;

class JsonHandler implements HandlerInterface
{
    use Traits\ExceptionMessage, Traits\ExceptionStack;

    protected function getData(Throwable $e): array
    {
        $id = \sha1($e->getMessage());

        $source = [];
        foreach ($this->getStack($e) as $trace) {
            $source[] = [
                'exception' => \get_class($trace->getException()).': '.$trace->getException()->getMessage(),
                'trace' => \array_map(function (Frame $frame) {
                    return $frame->toString();
                }, $trace->getFrames()),
            ];
        }

        return [
            'id' => $id,
            'links' => [
                'self' => $_SERVER['REQUEST_URI'] ?? '/',
            ],
            'status' => 500,
            'code' => $e->getCode(),
            'title' => \sprintf('Uncaught %s', get_class($e)),
            'detail' => $e->getMessage(),
            'source' => $source,
        ];
    }

    public function handle(Throwable $e): void
    {
        if (!\headers_sent()) {
            \header('Content-Type: application/json', true, 500);
        }
        echo \json_encode($this->getData($e), JSON_PRETTY_PRINT);
    }
}
