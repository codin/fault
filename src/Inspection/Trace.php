<?php

declare(strict_types=1);

namespace Codin\Fault\Inspection;

use Throwable;

class Trace
{
    protected Throwable $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }

    public function getExceptionClassName(): string
    {
        return \get_class($this->exception);
    }

    public function getFrames(): array
    {
        $frames = $this->exception->getTrace();

        $containsException = array_reduce($frames, function (bool $carry, array $frame): bool {
            return isset($frame['file'], $frame['line']) && $this->exception->getFile() === $frame['file'] && $this->exception->getLine() === $frame['line'] ? true : $carry;
        }, false);

        if (!$containsException) {
            array_unshift($frames, [
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
            ]);
        }

        return array_map(fn (array $params): Frame => Frame::create($params), $frames);
    }
}
