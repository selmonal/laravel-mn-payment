<?php

namespace spec\Selmonal\Payment\Gateways\Golomt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\Gateways\Golomt\TransactionValidator;
use Selmonal\Payment\ResponseInterface;

class ResponseSpec extends ObjectBehavior
{
    public function let(BillableInterface $billable)
    {
        $this->beConstructedWith(
            $billable, 1, '000', 'Амжилттай гүйлгээ', '548-791-310'
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Selmonal\Payment\Gateways\Golomt\Response');
        $this->shouldHaveType('Selmonal\Payment\ResponseInterface');
    }

    public function it_validates(TransactionValidator $validator, BillableInterface $billable)
    {
        $billable->getPaymentPrice()->willReturn(500);
        $billable->getBillableId()->willReturn('billable-id');

        $validator->handle('billable-id', 500)->shouldBeCalled();

        $this->setValidator($validator);

        $this->validate();
    }

    public function it_has_a_billable(BillableInterface $billable)
    {
        $this->getBillable()->shouldEqual($billable);
    }

    public function it_has_a_status()
    {
        $this->getStatus()->shouldEqual(ResponseInterface::STATUS_APPROVED);
    }

    public function it_has_a_message()
    {
        $this->getMessage()->shouldEqual('Амжилттай гүйлгээ');
    }

    public function it_has_a_card_number()
    {
        $this->getCardNumber()->shouldEqual('548-791-310');
    }
}
