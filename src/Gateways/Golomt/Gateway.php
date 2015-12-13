<?php

namespace Selmonal\Payment\Gateways\Golomt;

use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\Exceptions\InvalidResponseException;
use Selmonal\Payment\GatewayInterface;
use Selmonal\Payment\RedirectForm;
use Selmonal\Payment\ResponseInterface;

class Gateway implements GatewayInterface
{
    private $merchantId;
    private $requestAction;

    public function __construct($merchantId, $requestAction)
    {
        $this->merchantId = $merchantId;

        $this->requestAction = $requestAction;
    }

    /**
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function makeRequestForm(BillableInterface $billable)
    {
        $form = new RedirectForm($this->requestAction, 'POST');

        $form->putParam('trans_amount', $billable->getPaymentPrice());
        $form->putParam('trans_number', $billable->getBillableId());
        $form->putParam('key_number', $this->merchantId);

        return $form;
    }

    /**
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     * @throws InvalidResponseException
     */
    public function handleResponse(BillableInterface $billable, $params = [])
    {
        if ($params['trans_number'] != $billable->getBillableId()) {
            throw new InvalidResponseException('Billable id does not match with trans number');
        }

        return new Response(
            $billable,
            $params['success'],
            $params['error_code'],
            $params['error_desc'],
            $params['card_number']
        );
    }
}
