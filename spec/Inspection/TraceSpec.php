<?php

namespace spec\Codin\Fault\Inspection;

use Codin\Fault\Inspection\Frame;
use Exception;
use PhpSpec\ObjectBehavior;

class TraceSpec extends ObjectBehavior
{
    public function it_should_return_frames()
    {
        $a = new Exception('test 1');
        $this->beConstructedWith($a);
        $this->getFrames()->shouldBeArray();
        $this->getFrames()[0]->shouldBeAnInstanceOf(Frame::class);
    }

    public function it_should_return_exception()
    {
        $a = new Exception('test 2');
        $this->beConstructedWith($a);
        $this->getException()->shouldEqual($a);
    }
}
