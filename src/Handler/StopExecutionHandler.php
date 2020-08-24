<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Throwable;

class StopExecutionHandler implements HandlerInterface
{
    /**
     * @var int
     */
    protected $exitCode;

    public function __construct(int $exitCode = 1)
    {
        $this->exitCode = $exitCode;
    }

    public function handle(Throwable $e): void
    {
        exit($this->exitCode);
    }
}
