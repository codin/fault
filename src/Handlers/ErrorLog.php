<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Codin\Fault\Traits;
use Throwable;

class ErrorLog implements ExceptionHandler
{
    use Traits\ExceptionMessage;

    public function handle(Throwable $e): void
    {
        \error_log($this->getMessageWithSource($e));
    }
}
