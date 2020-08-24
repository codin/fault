<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Codin\Fault\Traits;
use Throwable;

class ErrorLogHandler implements HandlerInterface
{
    use Traits\ExceptionMessage;

    public function handle(Throwable $e): void
    {
        \error_log($this->getMessageWithSource($e));
    }
}
