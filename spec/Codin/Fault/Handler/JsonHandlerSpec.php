<?php

namespace spec\Codin\Fault\Handler;

use ErrorException;
use PhpSpec\ObjectBehavior;

class JsonHandlerSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions()
    {
        ob_start();
        $this->handle(new ErrorException('test error'));
        ob_end_clean();
    }
}
