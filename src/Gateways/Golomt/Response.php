<?php

namespace Selmonal\Payment\Gateways\Golomt;

use Illuminate\Support\Facades\App;
use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * Error codes.
     *
     * @var array
     */
    private static $errorStatuses = [
        ResponseInterface::STATUS_CANCELLED_BY_CARDHOLDER => [
            '203'
        ],
        ResponseInterface::STATUS_DECLINED => [
            '202', '305', '300-12', '300-14', '300-51', '300-54', '300-58'
        ],
        ResponseInterface::STATUS_FAILED => [
            '201', '300-89', '902-96'
        ],
        ResponseInterface::STATUS_TIMED_OUT => [
            '902-91'
        ]
    ];

    /**
     * The billable instance.
     *
     * @var BillableInterface
     */
    private $billable;

    /**
     * Success status. 0,1
     *
     * @var integer
     */
    private $success;

    /**
     * Response error code.
     *
     * @var string
     */
    private $errorCode;

    /**
     * Response error description.
     *
     * @var string
     */
    private $errorDescription;

    /**
     * Response card number.
     *
     * @var string
     */
    private $cardNumber;

    /**
     * The validator instance.
     *
     * @var TransactionValidator
     */
    private $validator;

    /**
     * Response Constructor.
     *
     * @param BillableInterface $billable
     * @param $success
     * @param $errorCode
     * @param $errorDescription
     * @param $cardNumber
     */
    public function __construct(BillableInterface $billable, $success, $errorCode, $errorDescription, $cardNumber)
    {
        $this->billable = $billable;
        $this->success = $success;
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
        $this->cardNumber = $cardNumber;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->isApproved() ?
            ResponseInterface::STATUS_APPROVED : $this->getErrorStatus($this->getErrorCode());
    }

    /**
     * Parse the given error code to response status.
     *
     * @param $errorCode
     * @return int|string
     */
    public function getErrorStatus($errorCode)
    {
        foreach (self::$errorStatuses as $status => $codes) {
            if (in_array($errorCode, $codes)) {
                return $status;
            }
        }

        return ResponseInterface::STATUS_DECLINED;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()
    {
        return $this->errorDescription;
    }

    /**
     * {@inheritDoc}
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * {@inheritDoc}
     */
    public function validate()
    {
        $this->getValidator()->handle(
            $this->getBillable()->getBillableId(),
            $this->getBillable()->getPaymentPrice()
        );
    }

    /**
     * Set the validator instance.
     *
     * @param TransactionValidator $validator
     */
    public function setValidator(TransactionValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the card number.
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Get the validator instance.
     *
     * @return TransactionValidator
     */
    public function getValidator()
    {
        return $this->validator ?
            $this->validator : App::make('Selmonal\Payment\Gateways\Golomt\TransactionValidator');
    }

    /**
     * Get the error code.
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * {@inheritDoc}
     */
    public function isApproved()
    {
        return $this->success == 0;
    }
}
