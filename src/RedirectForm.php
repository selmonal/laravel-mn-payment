<?php

namespace Selmonal\Payment;

class RedirectForm
{
    private $action;

    private $method;

    private $params = [];

    public function __construct($action, $method = 'POST')
    {
        $this->action = $action;
        $this->method = $method;
    }

    public function putParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getAction()
    {
        return $this->action;
    }
    /**
     * @return string
     */
    public function render()
    {
        return '<html>
                <head>
                    <title>Redirecting</title>
                </head>
                <body onload="document.getElementById(\'form\').submit()">
                    <h3>Redirecting...</h3>
                    <form action="' . $this->getAction() . '" method="' . $this->getMethod() . '" id="form">
                        '. $this->paramsToHtml() .'
                    </form>
                </body>
            </html>';
    }

    /**
     * @return string
     */
    private function paramsToHtml()
    {
        $html = '';
        foreach ($this->getParams() as $key => $value) {
            $html .= '<input type="hidden" value="' . $value . '" name="'. $key .'">';
        }
        return $html;
    }
}
