<?php

namespace spec\Codin\Fault\Handler;

use PhpSpec\ObjectBehavior;
use Throwable;

class CallbackHandlerSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        $this->beConstructedWith(static function (Throwable $exception): void {
        });
        $this->handle($exception);
    }
}
