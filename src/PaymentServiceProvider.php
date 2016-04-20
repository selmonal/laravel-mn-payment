<?php

namespace Selmonal\Payment;

use Config;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Selmonal\Payment\Gateways\Golomt\Gateway as GolomtGateway;
use Selmonal\Payment\Gateways\Khan\Gateway as KhanGateway;
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
        $this->registerGolomt();
        $this->registerKhan();

        $this->app->bindShared('Selmonal\Payment\PaymentManager', function () {

            $manager = new PaymentManager();

            return $manager->with(Config::get('payment.default'));

        });
    }

    /**
     * Голомт банкийг бүртгэх.
     */
    private function registerGolomt()
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
    }

    /**
     * Хаан банкийг бүртгэх
     */
    private function registerKhan()
    {
        $this->app->bindShared('Selmonal\Payment\Gateways\Khan\Gateway', function () {
            return new KhanGateway(
                new Client(),
                Config::get('payment.gateways.khan.username'),
                Config::get('payment.gateways.khan.password')
            );
        });
    }
}
