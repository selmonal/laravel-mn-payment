<?php

namespace Selmonal\Payment\Gateways\StateBank;

class HttpClient
{
    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * HttpClient Constructor.
     *
     * @param $server
     * @param $username
     * @param $password
     */
    public function __construct($server, $username, $password)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
    }
}
