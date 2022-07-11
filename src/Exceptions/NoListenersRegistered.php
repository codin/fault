<?php

declare(strict_types=1);

namespace Codin\Fault\Exceptions;

use Exception;

class NoListenersRegistered extends Exception
{
    public static function noneRegistered(): self
    {
        return new self('No listeners have been registered before attempting to register the error handler');
    }
}
