<?php

namespace spec\Codin\Fault\Handler;

use PhpSpec\ObjectBehavior;
use Throwable;

class ErrorLogHandlerSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        $this->handle($exception);
    }
}
