<?php

namespace spec\Selmonal\Payment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedirectFormSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith('action', 'POST');		
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\RedirectForm');

        $this->getMethod()->shouldEqual('POST');
        $this->getAction()->shouldEqual('action');
    }

    function it_can_add_optional_parameters()
    {
    	$this->putParam('amount', 500);
    	$this->getParams()->shouldHaveCount(1);

    	$this->putParam('key', 'key');
    	$this->getParams()->shouldHaveCount(2);

    	$this->getParams()->shouldEqual(['amount' => 500, 'key' => 'key']);
    }
}
