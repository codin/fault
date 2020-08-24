<?php

namespace spec\Codin\Fault\Handler;

use ErrorException;
use PhpSpec\ObjectBehavior;

class SyslogHandlerSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions()
    {
        $this->beConstructedWith('app', function () {});
        $this->handle(new ErrorException('test error'));
    }
}
