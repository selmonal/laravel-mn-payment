<?php

namespace spec\Selmonal\Payment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\GatewayInterface;

class PaymentManagerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\PaymentManager');
    }

    public function it_can_change_gateway(GatewayInterface $gateway)
    {
        $this->setGateway($gateway);
        $this->getGateway()->shouldEqual($gateway);
    }

    public function it_is_a_gateway()
    {
        $this->shouldHaveType('Selmonal\Payment\GatewayInterface');
    }
}
