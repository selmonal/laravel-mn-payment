<?php
/**
 * User: selmonal
 * Date: 12/13/15
 * Time: 7:00 PM
 */

namespace Selmonal\Payment;

use Config;
use Illuminate\Support\ServiceProvider;
use Selmonal\Payment\Gateways\Golomt\Gateway as GolomtGateway;
use Selmonal\Payment\Gateways\Golomt\TransactionValidator;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('Selmonal\Payment\Gateways\Golomt\Gateway', function () {
            return new GolomtGateway(
                Config::get('payment.gateways.golomt.merchant_id'),
                Config::get('payment.gateways.golomt.request_action'),
                Config::get('payment.gateways.golomt.subID')
            );
        });

        $this->app->bindShared('Selmonal\Payment\Gateways\Golomt\TransactionValidator', function () {
            return new TransactionValidator(
                Config::get('payment.gateways.golomt.soap_username'),
                Config::get('payment.gateways.golomt.soap_password'),
                Config::get('payment.gateways.golomt.wsdl')
            );
        });

        $this->app->bindShared('Selmonal\Payment\PaymentManager', function () {
            $manager = new PaymentManager();

            return $manager->using(Config::get('payment.gateways.default'));
        });
    }
}
