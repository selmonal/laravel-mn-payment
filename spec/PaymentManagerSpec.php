<?php

namespace spec\Selmonal\Payment;

use Illuminate\Support\Facades\App;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\GatewayInterface;
use Mockery as m;

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

    public function it_can_use_golomt()
    {
        App::shouldReceive('make')
            ->with('Selmonal\Payment\Gateways\Golomt\Gateway')
            ->andReturn($gateway = m::mock('Selmonal\Payment\Gateways\Golomt\Gateway'));

        $this->using('golomt');

        $this->getGateway()->shouldEqual($gateway);
    }

    public function it_should_throw_an_exception_when_an_invalid_gateway_provided_to_using()
    {
        $this->shouldThrow('Selmonal\Payment\Exceptions\UnsupportedPaymentGatewayException')->duringUsing('invalid-gateway');
    }
}
