<?php

namespace Selmonal\Payment;

interface GatewayInterface
{
    /**
     * Банкны терминал хуудас уруу үсрэх формыг буцаана.
     *
     * @param  BillableInterface $billable
     * @param  $lang
     * @return RedirectForm
     */
    public function makeRequestForm(BillableInterface $billable, $lang = 'mn');

    /**
     * Банкнаас буцаж ирсэн хариултыг боловсруулна.
     *
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     */
    public function handleResponse(BillableInterface $billable, $params = []);
}
