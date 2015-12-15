<?php

namespace spec\Selmonal\Payment\Gateways\StateBank;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpClientSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('http://statebank.mn/', 'username', 'password');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\StateBank\HttpClient');
    }
}
