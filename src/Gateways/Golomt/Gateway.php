<?php

namespace Selmonal\Payment\Gateways\Golomt;

use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\Exceptions\InvalidResponseException;
use Selmonal\Payment\GatewayInterface;
use Selmonal\Payment\RedirectForm;
use Selmonal\Payment\ResponseInterface;

class Gateway implements GatewayInterface
{
    public static $langs = [
        'en' => 1,
        'mn' => 0
    ];

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
     * Gateway Constructgor.
     *
     * @param $merchantId
     * @param $requestAction
     * @param int $subID
     * @param int $lang
     */
    public function __construct($merchantId, $requestAction, $subID = 1)
    {
        $this->merchantId = $merchantId;
        $this->requestAction = $requestAction;
        $this->subID = $subID;
    }


    /**
     * {@inheritDoc}
     */
    public function makeRequestForm(BillableInterface $billable, $lang = 'mn')
    {
        $form = new RedirectForm($this->requestAction, 'POST');

        $form->putParam('trans_amount', $billable->getPaymentPrice());
        $form->putParam('trans_number', $billable->getBillableId());
        $form->putParam('key_number', $this->merchantId);
        $form->putParam('subID', $this->subID);
        $form->putParam('lang', $this->getLangId($lang));

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

    private function getLangId($lang)
    {
        if(! array_key_exists($lang, self::$langs)) {
            throw new \InvalidArgumentException('Lang must be en or mn.');
        }

        return self::$langs[$lang];
    }
}
