<?php

declare(strict_types=1);

namespace Codin\Fault\Contracts;

use Throwable;

interface ExceptionHandler
{
    public function handle(Throwable $exception): void;
}
