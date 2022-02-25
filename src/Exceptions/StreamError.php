<?php

declare(strict_types=1);

namespace Codin\Fault\Exceptions;

use Exception;

class StreamError extends Exception
{
    public static function failedOpening(string $io): self
    {
        return new self('failed to open stream: '.$io);
    }
}
