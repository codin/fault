<?php

namespace spec\Codin\Fault;

use Codin\Fault\Truncation;
use PhpSpec\ObjectBehavior;

class TruncationSpec extends ObjectBehavior
{
    public function it_should_shorten_strings()
    {
        $this->beConstructedWith('abcdef', 3, false);
        $this->truncate()->shouldEqual('abc');
    }
}
