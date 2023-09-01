<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use UnexpectedValueException;

class AuthorizationCheck extends Middleware
{

    function __construct()
    {
    }

    public function handle($next)
    {
        try {
            JWT::decode(str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"]), new Key($_ENV['JWT_KEY'], 'HS256'));
            return $next();
        } catch (ExpiredException $err) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => [
                    "token" =>  ["Token Expired"]
                ],
            ], 401);
        } catch (UnexpectedValueException $err) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => [
                    "token" =>  ["No Token found"]
                ],
            ], 401);
        }
    }
}
