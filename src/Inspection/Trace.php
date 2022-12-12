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
        return array_map(fn (array $params): Frame => Frame::create($params), $this->exception->getTrace());
    }
}
