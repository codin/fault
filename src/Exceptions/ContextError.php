<?php

declare(strict_types=1);

namespace Codin\Fault\Exceptions;

use Exception;

class ContextError extends Exception
{
    public static function fileNotFound(string $file): self
    {
        return new self('context file is not readable: '.$file);
    }

    public static function invalidLineNumber(): self
    {
        return new self('context line number cannot be less than 1');
    }

    public static function notAvailable(): self
    {
        return new self('context not available');
    }
}
