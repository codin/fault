<?php

declare(strict_types=1);

namespace Codin\Fault\Exceptions;

use Exception;

class FrameError extends Exception
{
    /**
     * @param mixed $value
     */
    public static function normaliseUnknown($value): self
    {
        return new self('failed to normalise value of type: '.gettype($value));
    }
}
