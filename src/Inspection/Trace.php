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

    public function getContext(): Context
    {
        return new Context($this->exception->getFile(), $this->exception->getLine());
    }

    public function getFrames(): array
    {
        $frames = [];

        foreach ($this->exception->getTrace() as $params) {
            $frames[] = new Frame(
                isset($params['file']) ? (string) $params['file'] : null,
                isset($params['line']) ? (int) $params['line'] : null,
                isset($params['class'], $params['type'], $params['function']) ?
                    sprintf('%s%s%s', $params['class'], $params['type'], $params['function']) :
                    (isset($params['function']) ? $params['function'] : null),
                $params['args']
            );
        }

        return $frames;
    }
}
