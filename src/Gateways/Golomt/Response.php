<?php

namespace Selmonal\Payment\Gateways\Golomt;

use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\ResponseInterface;

class Response implements ResponseInterface
{
	/**
	 * @var array
	 */
	private static $errorCodes = [
		ResponseInterface::STATUS_APPROVED => ['000'],
		ResponseInterface::STATUS_CANCELLED_BY_CARDHOLDER => ['203'],
		ResponseInterface::STATUS_DECLINED => [
			'202', '305', '300-12', '300-14', '300-51', '300-54', '300-58'
		],
		ResponseInterface::STATUS_FAILED => [
			'201', '300-89', '902-96'
		],
		ResponseInterface::STATUS_TIMED_OUT => ['902-91']
	];

	private $billable;
	private $success;
	private $errorCode;
	private $errorDescription;
	private $cardNumber;
	private $validator;

	public function __construct(BillableInterface $billable, $success, $errorCode, $errorDescription, $cardNumber)
	{
		$this->billable = $billable;
		$this->success = $success;
		$this->errorCode = $errorCode;
		$this->errorDescription = $errorDescription;
		$this->cardNumber = $cardNumber;
	}


	function getStatus()
	{
		foreach(self::$errorCodes as $status => $codes) {
			if(in_array($this->errorCode, $codes)) {
				return $status;
			}
		}

		return ResponseInterface::STATUS_DECLINED;
	}

	function getMessage()
	{
		return $this->errorDescription;
	}

	function getBillable()
	{
		return $this->billable;
	}

	function validate()
	{
		$this->getValidator()->handle(
			$this->getBillable()->getBillableId(),
			$this->getBillable()->getPaymentPrice()
		);
	}

    public function setValidator(TransactionValidator $validator)
    {
        $this->validator = $validator;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function getValidator()
    {
        return $this->validator ?
			$this->validator : \App::make('Selmonal\Payment\Gateways\Golomt\TransactionValidator');
    }
}
