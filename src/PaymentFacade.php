<?php
/**
 * User: selmonal
 * Date: 12/13/15
 * Time: 8:08 PM
 */

namespace Selmonal\Payment;

use Illuminate\Support\Facades\Facade;

class PaymentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Selmonal\Payment\PaymentManager';
    }
}
