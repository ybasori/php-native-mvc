<?php

namespace App\Middlewares;


class ExampleSuccessMiddleware extends Middleware
{

    public function handle($next)
    {
        return $next();
    }
}
