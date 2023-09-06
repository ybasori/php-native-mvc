<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use UnexpectedValueException;

class AdminCheck extends Middleware
{

    function __construct()
    {
    }

    public function handle($next)
    {
        try {
            $code = JWT::decode(str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"]), new Key($_ENV['JWT_KEY'], 'HS256'));

            if (!empty($code->author->role) && ($code->author->role == "superadmin" || $code->author->role == "admin")) {
                return $next();
            }
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => [
                    "token" =>  ["Token Invalid"]
                ],
            ], 401);
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
