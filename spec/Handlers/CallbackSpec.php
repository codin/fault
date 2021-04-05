<?php

namespace spec\Codin\Fault\Handlers;

use PhpSpec\ObjectBehavior;
use Throwable;

class CallbackSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        $this->beConstructedWith(static function (Throwable $exception): void {
        });
        $this->handle($exception);
    }
}
