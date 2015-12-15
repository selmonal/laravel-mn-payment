<?php

namespace Selmonal\Payment;

class RedirectForm
{
    /**
     * Form action.
     *
     * @var string
     */
    private $action;

    /**
     * Form method. POST|GET
     *
     * @var string
     */
    private $method;

    /**
     * The params that should be rendered as hidden
     * fields.
     *
     * @var array
     */
    private $params = [];

    /**
     * RedirectForm Constructor.
     *
     * @param $action
     * @param string $method
     */
    public function __construct($action, $method = 'POST')
    {
        $this->action = $action;
        $this->method = $method;
    }

    /**
     * Put a parameter.
     *
     * @param $key
     * @param $value
     */
    public function putParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get the method of the form.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the action of the form.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Render the real html form. That redirects
     * automatically.
     *
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
     * Convert params to hidden fields.
     *
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
