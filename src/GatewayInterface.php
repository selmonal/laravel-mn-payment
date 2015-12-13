<?php

namespace Selmonal\Payment;

interface GatewayInterface
{
    /**
     * Make a RedirectForm for the given
     * billable.
     *
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function makeRequestForm(BillableInterface $billable);

    /**
     * Handle response for the given billable.
     *
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     */
    public function handleResponse(BillableInterface $billable, $params = []);
}
