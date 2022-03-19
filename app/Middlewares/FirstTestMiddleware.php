<?php

namespace App\Middlewares;


class FirstTestMiddleware
{

    public function handle($next)
    {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode((object) [
            "message" => "Something went wrong!"
        ]);

        // return $next();
    }
}
