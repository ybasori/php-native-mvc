<?php

namespace App\Middlewares;


class ExampleFailMiddleware extends Middleware
{

    public function handle()
    {
        return $this->json([
            "message" => "Fail",
        ], 400);
    }
}
