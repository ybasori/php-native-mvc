<?php

namespace App\Middlewares;

class XmlHttpRequestCheck extends Middleware
{

    function __construct()
    {
    }

    public function handle($next)
    {

        if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            return $this->view("layout");
        } else {
            return $next();
        }
    }
}
