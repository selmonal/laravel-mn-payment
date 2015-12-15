<?php 

namespace Selmonal\Payment;

use Selmonal\Payment\Exceptions\TransactionValidationException;

interface ResponseInterface
{
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLED_BY_CARDHOLDER = 'cancelled';
    const STATUS_FAILED = 'failed';
    const STATUS_TIMED_OUT = 'timeout';

    /**
     * Get the response status.
     *
     * @return mixed
     */
    public function getStatus();

    /**
     * Get the response message.
     *
     * @return string
     */
    public function getMessage();

    /**
     * Get the billable message.
     *
     * @return BillableInterface
     */
    public function getBillable();

    /**
     * Define the response is approved.
     *
     * @return boolean
     */
    public function isApproved();

    /**
     * Validate the response.
     *
     * @return boolean
     * @throws TransactionValidationException
     */
    public function validate();
}
