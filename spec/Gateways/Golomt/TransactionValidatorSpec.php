<?php

namespace spec\Selmonal\Payment\Gateways\Golomt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionValidatorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('username', 'password', 'https://soup.golomt.mn/');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\Golomt\TransactionValidator');
    }
}
