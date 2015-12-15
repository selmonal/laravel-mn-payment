<?php

namespace spec\Selmonal\Payment\Gateways\Golomt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\Gateways\Golomt\TransactionValidator;
use Selmonal\Payment\ResponseInterface;

class ResponseSpec extends ObjectBehavior
{
    function let(BillableInterface $billable)
    {
        $this->beConstructedWith(
            $billable, 0, '000', 'Амжилттай гүйлгээ', '548-791-310'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\Golomt\Response');
    }

    function it_is_a_response()
    {
        $this->shouldHaveType('Selmonal\Payment\ResponseInterface');
    }

    function it_should_run_transasction_validator_on_validating(TransactionValidator $validator, BillableInterface $billable)
    {
        $billable->getPaymentPrice()->willReturn(500);
        $billable->getBillableId()->willReturn('billable-id');

        $validator->handle('billable-id', 500)->shouldBeCalled();

        $this->setValidator($validator);

        $this->validate();
    }

    function it_has_a_billable(BillableInterface $billable)
    {
        $this->getBillable()->shouldEqual($billable);
    }

    function it_has_an_error_code()
    {
        $this->getErrorCode()->shouldEqual('000');
    }

    function it_should_be_approved_when_the_success_is_0(BillableInterface $billable)
    {
        $this->beConstructedWith($billable, 0, '000', '', '');
        $this->getStatus()->shouldEqual(ResponseInterface::STATUS_APPROVED);
        $this->isApproved()->shouldEqual(true);
    }

    function it_should_be_declined_when_the_success_is_1(BillableInterface $billable)
    {
        $this->beConstructedWith($billable, 1, '202', '', '');
        $this->getStatus()->shouldEqual(ResponseInterface::STATUS_DECLINED);
        $this->isApproved()->shouldEqual(false);
    }

    function it_has_a_message()
    {
        $this->getMessage()->shouldEqual('Амжилттай гүйлгээ');
    }

    function it_has_a_card_number()
    {
        $this->getCardNumber()->shouldEqual('548-791-310');
    }
}
