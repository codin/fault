<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Throwable;

interface HandlerInterface
{
    public function handle(Throwable $exception): void;
}
