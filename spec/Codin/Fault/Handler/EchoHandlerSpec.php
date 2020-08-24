<?php

namespace spec\Codin\Fault\Handler;

use PhpSpec\ObjectBehavior;
use Throwable;

class EchoHandlerSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        ob_start();
        $exception->__toString()->willReturn('test error');
        $this->handle($exception);
        ob_end_clean();
    }
}
