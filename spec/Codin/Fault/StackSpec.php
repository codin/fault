<?php

namespace spec\Codin\Fault;

use Exception;
use PhpSpec\ObjectBehavior;

class StackSpec extends ObjectBehavior
{
    public function it_should_count_exceptions()
    {
        $a = new Exception('test 1');
        $b = new Exception('test 1', 0, $a);
        $this->beConstructedWith($b);
        $this->count()->shouldEqual(2);
    }
}
