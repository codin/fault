<?php

namespace spec\Codin\Fault\Handlers;

use PhpSpec\ObjectBehavior;
use Throwable;

class ErrorLogSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        $this->handle($exception);
    }
}
