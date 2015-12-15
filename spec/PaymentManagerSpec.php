<?php

namespace spec\Selmonal\Payment;

use Illuminate\Support\Facades\App;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\GatewayInterface;
use Mockery as m;

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

    function it_can_use_golomt()
    {
        App::shouldReceive('make')
            ->with('Selmonal\Payment\Gateways\Golomt\Gateway')
            ->andReturn($gateway = m::mock('Selmonal\Payment\Gateways\Golomt\Gateway'));

        $this->using('golomt');

        $this->getGateway()->shouldEqual($gateway);
    }

    function it_should_throw_an_exception_when_invalid_gateway_provided()
    {
        $this->shouldThrow('Selmonal\Payment\Exceptions\UnsupportedPaymentGatewayException')->duringUsing('invalid-gateway');
    }
}
