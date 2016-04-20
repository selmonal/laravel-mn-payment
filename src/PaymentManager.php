<?php

namespace Selmonal\Payment;

use Illuminate\Support\Facades\App;
use Selmonal\Payment\Exceptions\UnsupportedPaymentGatewayException;

class PaymentManager implements GatewayInterface
{
    /**
     * The current gateway.
     *
     * @var GatewayInterface
     */
    protected $gateway;

    /**
     * PaymentManager constructor.
     *
     * @param GatewayInterface $gateway
     */
    public function __construct(GatewayInterface $gateway = null)
    {
        $this->gateway = $gateway;
    }

    /**
     * Change the current gateway.
     *
     * @param GatewayInterface $gateway
     */
    public function setGateway(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Change the current gateway using a gateway name.
     *
     * @param $gatewayName
     * @return $this
     * @throws UnsupportedPaymentGatewayException
     */
    public function with($gatewayName)
    {
        $this->setGateway($this->makeGatewayUsingName($gatewayName));

        return $this;
    }

    /**
     * Get the current gateway.
     *
     * @return GatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Handle a response that responded by the bank.
     *
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     */
    public function handleResponse(BillableInterface $billable, $params = [])
    {
        return $this->getGateway()->handleResponse($billable, $params);
    }

    /**
     * Make a gateway instance by the given name.
     *
     * @param $gatewayName
     * @throws UnsupportedPaymentGatewayException
     */
    public function makeGatewayUsingName($gatewayName)
    {
        if ($gatewayName == 'golomt') {
            return App::make('Selmonal\Payment\Gateways\Golomt\Gateway');
        }

        if ($gatewayName == 'khan') {
            return App::make('Selmonal\Payment\Gateways\Khan\Gateway');
        }

        throw new UnsupportedPaymentGatewayException($gatewayName);
    }

    /**
     * Банкны терминал хуудас уруу үсрэх формыг буцаана.
     *
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function make(BillableInterface $billable)
    {
        return $this->getGateway()->make($billable);
    }

    /**
     * Буцах хаяг оноох
     *
     * @param $callback
     * @return $this
     */
    public function callback($callback)
    {
        $this->getGateway()->callback($callback);

        return $this;
    }

    /**
     * Банкны харагдацын хэл сонгох.
     *
     * @param $lang
     * @return $this
     */
    public function lang($lang)
    {
        $this->getGateway()->lang($lang);

        return $this;
    }

    /**
     * Нэмэлт параметер утга оноох
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function put($key, $value)
    {
        $this->getGateway()->put($key, $value);
    }
}
