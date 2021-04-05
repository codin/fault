<?php

namespace spec\Codin\Fault\Handlers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\TypeToken;
use Psr\Log\LoggerInterface;
use Throwable;

class PsrLoggerSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception, LoggerInterface $logger)
    {
        $this->beConstructedWith($logger);
        $logger->error(new TypeToken('string'), new TypeToken('array'))->shouldBeCalled();
        $this->handle($exception);
    }
}
