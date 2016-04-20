<?php

namespace Selmonal\Payment\Gateways\Khan;


use GuzzleHttp\Client;
use Selmonal\Payment\BillableInterface;
use Selmonal\Payment\GatewayInterface;
use Selmonal\Payment\RedirectForm;

class Gateway implements GatewayInterface
{
    /**
     * @var string
     */
    private $callback;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var array
     */
    private $additional = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Gateway constructor.
     *
     * @param Client $client
     * @param string $username
     * @param string $password
     */
    public function __construct(Client $client, $username, $password)
    {
        $this->client   = $client;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * {@inheritDoc}
     */
    public function handleResponse(BillableInterface $billable, $params = [])
    {

    }

    /**
     * Банкны терминал хуудас уруу үсрэх формыг буцаана.
     *
     * @param  BillableInterface $billable
     * @return RedirectForm
     */
    public function make(BillableInterface $billable)
    {
        $rand = rand(0,99999999);
        $orderNumber = date('YmdHis').$rand;

        $params = [
            'userName'    => $this->username,
            'password'    => $this->password,
            'amount'      => $billable->getPaymentPrice(),
            'orderNumber' => $orderNumber,
            'description' => $billable->getPaymentDescription(),
            'currency'    => $billable->getCurrencyCode(),
            'language'    => $this->lang,
            'pageView'    => 'DESKTOP',
            'returnUrl'   => $this->callback,
            'jsonParams'  => array_merge($this->additional, [
                'orderNumber' => $orderNumber
            ])
        ];

        $query = http_build_query($params);

        $response = $this->client->get('https://epp.khanbank.com/payment/rest/register.do?' . $query);

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents());

        return new RedirectForm($json->formUrl, 'get');
    }

    /**
     * Буцах хаяг оноох
     *
     * @param $callback
     * @return $this
     */
    public function callback($callback)
    {
        $this->callback = $callback;

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
        $this->lang = $lang;

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
        $this->additional[$key] = $value;

        return $this;
    }
}