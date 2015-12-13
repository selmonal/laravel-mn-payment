<?php

namespace Selmonal\Payment\Gateways\Golomt;

use Selmonal\Payment\Exceptions\TransactionValidationException;

class TransactionValidator
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $wsdl;

    /**
     * @param $username
     * @param $password
     * @param $wsdl
     */
    public function __construct($username, $password, $wsdl)
    {
        $this->username = $username;
        $this->password = $password;
        $this->wsdl = $wsdl;
    }

    /**
     * Validate a transaction.
     *
     * @param $trans_key
     * @param $amount
     * @param bool|string $date
     * @return bool
     * @throws TransactionValidationException
     * @throws SoupClientErrorException
     */
    public function handle($trans_key, $amount, $date = null)
    {
        $client = $this->makeClient();

        $result = $client->call('Get_new', $this->getParams($trans_key, $amount, $date), '', '', false, true);

        if(is_object($result) && $result->fault) {
            throw new SoupClientErrorException(print_r($result));
        }

        if($error = $client->getError()) {
            throw new SoupClientErrorException($error);
        }

        $responseCode = $result['Get_newResult'];

        if(strlen($responseCode) != 6) {
            throw new TransactionValidationException(
                $this->getResponseMessage($responseCode), $responseCode
            );
        }

        return true;
    }

    /**
     * Make a new nusoap_client instance.
     *
     * @return nusoap_client
     * @throws SoupClientErrorException
     */
    private function makeClient()
    {
        $client = new \nusoap_client($this->wsdl, 'wsdl');

        if(! $client->getError()) {
            return $client;
        }

        throw new SoupClientErrorException('Could not make a nusoap_client instance.');
    }

    /**
     * Get the prepared params.
     *
     * @param $trans_key
     * @param $amount
     * @param $date
     * @return array
     */
    private function getParams($trans_key, $amount, $date)
    {
        return [
            'v0' => $this->username,
            'v1' => $this->password,
            'v2' => $trans_key,
            'v3' => is_null($date) ? date('Ymd') : $date,
            'v4' => number_format($amount , 2, '.', '')
        ];
    }

    /**
     * @param $responseCode
     * @return string
     */
    private function getResponseMessage($responseCode)
    {
        switch($responseCode) {
            case 2: return 'Guilgee amjiltgui bolson baina.'; break;
            case 0: return 'Iim dugaar bolon guilgeenii duntei guilgee baazad burtgegdeegui baina.'; break;
            case 3: return 'Hereglegchiin ner esvel nuuts ug buruu baina.'; break;
        }
    }
}
