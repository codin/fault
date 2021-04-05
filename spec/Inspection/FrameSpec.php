<?php

namespace spec\Codin\Fault\Inspection;

use PhpSpec\ObjectBehavior;

class FrameSpec extends ObjectBehavior
{
    public function it_is_return_the_file_and_line()
    {
        $path = sys_get_temp_dir();
        $name = sprintf('phpspec-test-%u', random_int(1, PHP_INT_MAX));
        $filepath = sprintf('%s/%s.php', $path, $name);

        file_put_contents($filepath, implode("\n", range(1, 100))."\n");

        $this->beConstructedWith($filepath, 50);
        $this->getFile()->shouldReturn($filepath);
        $this->getLine()->shouldReturn(50);
    }
}
