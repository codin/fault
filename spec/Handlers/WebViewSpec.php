<?php

namespace spec\Codin\Fault\Handlers;

use PhpSpec\ObjectBehavior;
use Throwable;

class WebViewSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        ob_start();
        $this->handle($exception);
        ob_end_clean();
    }
}
