<?php

namespace spec\Codin\Fault\Handlers;

use ErrorException;
use PhpSpec\ObjectBehavior;

class SystemLogSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions()
    {
        $this->beConstructedWith('app');
        $this->handle(new ErrorException('test error'));
    }
}
