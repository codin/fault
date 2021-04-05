<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Throwable;

class StopExecution implements ExceptionHandler
{
    protected int $exitCode;

    public function __construct(int $exitCode = 1)
    {
        $this->exitCode = $exitCode;
    }

    public function handle(Throwable $e): void
    {
        exit($this->exitCode);
    }
}
