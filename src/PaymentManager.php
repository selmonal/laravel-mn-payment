<?php
/**
 * User: selmonal
 * Date: 12/13/15
 * Time: 8:09 PM
 */

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
    public function using($gatewayName)
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
     * Make a redirect form. That redirects to the gateway
     * bank terminal.
     *
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function makeRequestForm(BillableInterface $billable)
    {
        return $this->getGateway()->makeRequestForm($billable);
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

        throw new UnsupportedPaymentGatewayException($gatewayName);
    }
}
