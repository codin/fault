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
            $frames[] = Frame::create($params);
        }

        return $frames;
    }
}
