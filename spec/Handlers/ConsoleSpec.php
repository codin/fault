<?php

namespace spec\Codin\Fault\Handlers;

use PhpSpec\ObjectBehavior;
use Throwable;

class ConsoleSpec extends ObjectBehavior
{
    public function it_should_handle_exceptions(Throwable $exception)
    {
        $path = sys_get_temp_dir();
        $name = sprintf('phpspec-test-%u', random_int(1, PHP_INT_MAX));
        $filepath = sprintf('%s/%s.txt', $path, $name);

        $this->beConstructedWith($filepath);
        $this->handle($exception);

        unlink($filepath);
    }
}
