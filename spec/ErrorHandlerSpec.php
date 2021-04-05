<?php

namespace spec\Codin\Fault;

use Codin\Fault\Contracts\ExceptionHandler;
use Exception;
use PhpSpec\ObjectBehavior;

class ErrorHandlerSpec extends ObjectBehavior
{
    public function it_should_process_handlers(ExceptionHandler $listener)
    {
        $this->attach($listener);
        $exception = new Exception('doh');
        $listener->handle($exception)->shouldBeCalled();
        $this->onException($exception);
    }
}
