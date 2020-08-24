<?php

namespace spec\Codin\Fault;

use Codin\Fault\Frame;
use PhpSpec\ObjectBehavior;

class FrameSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Frame::class);
    }
}
