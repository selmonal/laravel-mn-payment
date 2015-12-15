<?php

namespace spec\Selmonal\Payment\Gateways\StateBank;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GatewaySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\StateBank\Gateway');
    }

    function it_is_a_gateway()
    {
        $this->shouldHaveType('Selmonal\Payment\GatewayInterface');
    }
}
