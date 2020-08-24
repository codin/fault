<?php

namespace spec\Codin\Fault;

use Codin\Fault\ErrorHandler;
use Codin\Fault\Handler\HandlerInterface;
use PhpSpec\ObjectBehavior;
use Exception;

class ErrorHandlerSpec extends ObjectBehavior
{
    public function it_should_process_handlers(HandlerInterface $listener)
    {
        $this->attach($listener);
        $exception = new Exception('doh');
        $listener->handle($exception)->shouldBeCalled();
        $this->onException($exception);
    }
}
