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
     * @param GatewayInterface $gateway
     */
    public function setGateway(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
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
     * @return GatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function makeRequestForm(BillableInterface $billable)
    {
        return $this->getGateway()->makeRequestForm($billable);
    }

    /**
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     */
    public function handleResponse(BillableInterface $billable, $params = [])
    {
        return $this->getGateway()->handleResponse($billable, $params);
    }

    /**
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
