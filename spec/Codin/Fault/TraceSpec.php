<?php

namespace spec\Codin\Fault;

use Codin\Fault\Context;
use Codin\Fault\Frame;
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

    public function it_should_return_context()
    {
        $a = new Exception('test 3');
        $this->beConstructedWith($a);
        $this->getContext()->shouldBeAnInstanceOf(Context::class);
    }
}
