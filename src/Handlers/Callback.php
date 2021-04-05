<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Closure;
use Codin\Fault\Contracts\ExceptionHandler;
use Throwable;

class Callback implements ExceptionHandler
{
    protected Closure $callable;

    public function __construct(callable $callable)
    {
        $this->callable = Closure::fromCallable($callable);
    }

    public function handle(Throwable $exception): void
    {
        ($this->callable)($exception);
    }
}
