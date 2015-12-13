<?php

namespace spec\Selmonal\Payment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\GatewayInterface;

class PaymentManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\PaymentManager');
    }

    function it_can_change_gateway(GatewayInterface $gateway)
    {
        $this->setGateway($gateway);
        $this->getGateway()->shouldEqual($gateway);
    }

    function it_is_a_gateway()
    {
        $this->shouldHaveType('Selmonal\Payment\GatewayInterface');
    }
}
