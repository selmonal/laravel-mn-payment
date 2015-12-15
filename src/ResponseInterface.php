<?php 

namespace Selmonal\Payment;

interface ResponseInterface
{
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLED_BY_CARDHOLDER = 'cancelled';
    const STATUS_FAILED = 'failed';
    const STATUS_TIMED_OUT = 'timeout';
    
    public function getStatus();

    public function getMessage();

    public function getBillable();

    public function isApproved();

    public function validate();
}
