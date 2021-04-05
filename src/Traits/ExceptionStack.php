<?php

declare(strict_types=1);

namespace Codin\Fault\Traits;

use Codin\Fault\Inspection\Stack;
use Throwable;

trait ExceptionStack
{
    protected function getStack(Throwable $e): Stack
    {
        return new Stack($e);
    }
}
