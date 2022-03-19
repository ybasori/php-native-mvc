<?php

namespace App\Middlewares;


class SecondTestMiddleware
{

    public function handle($next)
    {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode((object) [
            "message" => "second test!"
        ]);

        // return $next();
    }
}
