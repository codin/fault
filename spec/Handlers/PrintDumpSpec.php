<?php

namespace spec\Codin\Fault\Handlers;

use PhpSpec\ObjectBehavior;
use Throwable;

class PrintDumpSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        ob_start();
        $exception->__toString()->willReturn('test error');
        $this->handle($exception);
        ob_end_clean();
    }
}
