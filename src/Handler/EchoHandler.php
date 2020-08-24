<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Throwable;

class EchoHandler implements HandlerInterface
{
    public function handle(Throwable $e): void
    {
        echo $e;
    }
}
