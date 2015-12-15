<?php

namespace Selmonal\Payment\Gateways\StateBank;

use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\GatewayInterface;
use Selmonal\Payment\RedirectForm;
use Selmonal\Payment\ResponseInterface;

class Gateway implements GatewayInterface
{
    /**
     * @var string
     */
    private $merchantId;

    /**
     * @param string $merchantId
     */
    public function __construct($merchantId = '')
    {
        $this->merchantId = $merchantId;
    }

    /**
     * Make a RedirectForm for the given
     * billable.
     *
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function makeRequestForm(BillableInterface $billable)
    {
        // TODO: Implement makeRequestForm() method.
    }

    /**
     * Handle response for the given billable.
     *
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     */
    public function handleResponse(BillableInterface $billable, $params = [])
    {
        // TODO: Implement handleResponse() method.
    }
}
