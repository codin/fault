<?php

declare(strict_types=1);

namespace Codin\Fault\Traits;

use ErrorException;
use Throwable;

trait ExceptionMessage
{
    private array $levels = [
        1 => 'E_ERROR',
        2 => 'E_WARNING',
        4 => 'E_PARSE',
        8 => 'E_NOTICE',
        16 => 'E_CORE_ERROR',
        32 => 'E_CORE_WARNING',
        64 => 'E_COMPILE_ERROR',
        128 => 'E_COMPILE_WARNING',
        256 => 'E_USER_ERROR',
        512 => 'E_USER_WARNING',
        1024 => 'E_USER_NOTICE',
        2048 => 'E_STRICT',
        4096 => 'E_RECOVERABLE_ERROR',
        8192 => 'E_DEPRECATED',
        16384 => 'E_USER_DEPRECATED',
    ];

    protected function getMessage(Throwable $e): string
    {
        $type = $e instanceof ErrorException ? $this->levels[$e->getSeverity()] : \get_class($e);
        return \sprintf('%s: %s', $type, $e->getMessage());
    }

    protected function getMessageWithSource(Throwable $e): string
    {
        return \sprintf(
            '%s in %s on %s',
            $this->getMessage($e),
            $e->getFile(),
            $e->getLine()
        );
    }
}
