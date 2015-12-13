<?php

namespace spec\Selmonal\Payment\Gateways\Golomt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\BillableInterface;

class GatewaySpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith('merchant-id', 'request-action');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\Golomt\Gateway');
    }

    function it_is_a_gateway()
    {
        $this->shouldHaveType('Selmonal\Payment\GatewayInterface');
    }

    function it_makes_a_redirect_form(BillableInterface $billable)
    {
    	$billable->getPaymentPrice()->willReturn(500);
    	$billable->getBillableId()->willReturn('billable-id');

    	$form = $this->makeRequestForm($billable);

    	$form->shouldHaveType('Selmonal\Payment\RedirectForm');

        $form->getParams()->shouldHaveCount(3);
        $form->getParams()->shouldHaveKeyWithValue('trans_amount', 500);
        $form->getParams()->shouldHaveKeyWithValue('trans_number', 'billable-id');
        $form->getParams()->shouldHaveKeyWithValue('key_number', 'merchant-id');
        $form->getMethod()->shouldEqual('POST');
        $form->getAction()->shouldEqual('request-action');
    }

    function it_handles_a_response(BillableInterface $billable)
    {
        $billable->getBillableId()->willReturn(651);

        $params = [
            'trans_number' => 651,
            'success' => 0,
            'error_code' => 2,
            'error_desc' => 'Тайлбар',
            'card_number' => '487-894-789'
        ];

        $response = $this->handleResponse($billable, $params);

        $response->getBillable()->shouldEqual($billable);
    }

    function it_should_not_handle_a_response_when_billable_id_did_not_match_with_trans_number(BillableInterface $billable)
    {
        $billable->getBillableId()->willReturn('billable-id');

        $params = ['trans_number' => 'wrong-billable-id'];

        $this->shouldThrow('Selmonal\Payment\Exceptions\InvalidResponseException')->duringHandleResponse($billable, $params);
    }
}
