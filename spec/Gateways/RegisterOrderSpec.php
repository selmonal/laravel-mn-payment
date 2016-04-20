<?php

namespace spec\Selmonal\Payment\Gateways;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegisterOrderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\RegisterOrder');
    }
}
