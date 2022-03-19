<?php

namespace App\Middlewares;

use App\Models\Admin;

class AuthorizationCheck extends Middleware
{

    function __construct()
    {
    }

    public function handle($next)
    {
        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {

            return $this->json([
                "errors" => [
                    "token" =>  "No Token found"
                ],
                "message" => "Something went wrong!"
            ], 401);
        }

        try {

            $user = new Admin;
            $user->validateToken($_SERVER['HTTP_AUTHORIZATION']);

            return $next();
        } catch (\Exception $err) {

            return $this->json([
                "errors" => [
                    "token" =>  $err->getMessage()
                ],
                "message" => "Something went wrong!"
            ], 401);
        }
    }
}
