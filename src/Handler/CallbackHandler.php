<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Closure;
use Throwable;

class CallbackHandler implements HandlerInterface
{
    /**
     * @var Closure
     */
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = Closure::fromCallable($callable);
    }

    public function handle(Throwable $exception): void
    {
        ($this->callable)($exception);
    }
}
