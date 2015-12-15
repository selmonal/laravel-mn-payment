<?php

namespace Selmonal\Payment\Gateways\Golomt;

use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\Exceptions\InvalidResponseException;
use Selmonal\Payment\GatewayInterface;
use Selmonal\Payment\RedirectForm;
use Selmonal\Payment\ResponseInterface;

class Gateway implements GatewayInterface
{
    /**
     * The merchant id.
     *
     * @var string
     */
    private $merchantId;

    /**
     * The url of the bank terminal page.
     *
     * @var string
     */
    private $requestAction;

    /**
     * Голомт банк 3 ширхэг буцах хаяг бүртгэх боломжтой байдаг.
     * Энд 1,2,3 гэсэн утга оноогдоно.
     *
     * @var string
     */
    private $subID;

    /**
     * The language code of the bank terminal.
     * 0 - mn, 2 - en
     *
     * @var string
     */
    private $lang;

    /**
     * Gateway Constructgor.
     *
     * @param $merchantId
     * @param $requestAction
     * @param int $subID
     * @param int $lang
     */
    public function __construct($merchantId, $requestAction, $subID = 1, $lang = 1)
    {
        $this->merchantId = $merchantId;
        $this->requestAction = $requestAction;
        $this->subID = $subID;
        $this->lang = $lang;
    }


    /**
     * {@inheritDoc}
     */
    public function makeRequestForm(BillableInterface $billable)
    {
        $form = new RedirectForm($this->requestAction, 'POST');

        $form->putParam('trans_amount', $billable->getPaymentPrice());
        $form->putParam('trans_number', $billable->getBillableId());
        $form->putParam('key_number', $this->merchantId);
        $form->putParam('subID', $this->subID);
        $form->putParam('lang', $this->lang);

        return $form;
    }

    /**
     * {@inheritDoc}
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
