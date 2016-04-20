<?php

namespace Selmonal\Payment;

interface GatewayInterface
{
    /**
     * Банкны терминал хуудас уруу үсрэх формыг буцаана.
     *
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function make(BillableInterface $billable);

    /**
     * Буцах хаяг оноох
     *
     * @param $callback
     * @return $this
     */
    public function callback($callback);

    /**
     * Банкны харагдацын хэл сонгох.
     *
     * @param $lang
     * @return $this
     */
    public function lang($lang);

    /**
     * Нэмэлт параметер утга оноох
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function put($key, $value);

    /**
     * Банкнаас буцаж ирсэн хариултыг боловсруулна.
     *
     * @param BillableInterface $billable
     * @param array $params
     * @return ResponseInterface
     */
    public function handleResponse(BillableInterface $billable, $params = []);
}
