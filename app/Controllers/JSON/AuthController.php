<?php

namespace App\Controllers\JSON;

use App\Controllers\Controller;
use App\Models\Author;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function login()
    {
        $dataLogin = $_POST;
        unset($dataLogin['remember']);


        $err = [];

        if (empty($dataLogin['email'])) {
            $err['email'][] = "E-mail is required!";
        }
        if (empty($dataLogin['password'])) {
            $err['password'][] = "Password is required!";
        }

        if (count($err) > 0) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => $err
            ], 400);
        }
        $user = new User;
        $usr = $user->get([
            "where" => [
                ["email", "=", $dataLogin['email']]
            ]
        ]);
        if ($usr) {
            if (password_verify($dataLogin['password'], $usr->password)) {
                $author = new Author;
                $mine = $author->get([
                    "where" => [
                        ["user_id", "=", $usr->id]
                    ]
                ]);
                $mine = (object) array_merge((array) $mine, ["email" => $usr->email]);
                $iat = time();
                $exp = $iat + (1 * 60 * 60);
                $key = $_ENV['JWT_KEY'];
                $payload = array(
                    "iss" => $_ENV['APP_URL'],
                    "aud" => $_ENV['APP_URL'],
                    "iat" => $iat,
                    "exp" => $exp,
                    "author" => $mine
                );

                $jwt = JWT::encode($payload, $key, 'HS256');

                unset($mine->id);
                unset($mine->user_id);

                return $this->json([
                    "message" => "Data found",
                    "data" => array_merge((array)$mine, [
                        "token" => $jwt,
                        "expires" => $exp
                    ]),
                ]);
            } else {

                return $this->json([
                    "message" => "Wrong email / password",
                    "data" => null,
                ], 400);
            }
        } else {
            return $this->json([
                "message" => "Wrong email / password",
                "data" => null,
            ], 400);
        }
    }

    public function register()
    {

        $dataReg = $_POST;
        unset($dataReg['retype_password']);

        $dataAuthor = ["username" => $dataReg['username']];
        unset($dataReg['username']);

        $err = [];

        if (empty($dataAuthor['username'])) {
            $err['username'][] = "Username is required!";
        }
        if (empty($dataReg['email'])) {
            $err['email'][] = "E-mail is required!";
        }
        if (empty($dataReg['password'])) {
            $err['password'][] = "Password is required!";
        }

        $user = new User;
        $author = new Author;
        if (count($err) == 0) {

            $checkEmail = $user->checkDuplicate("email", $dataReg['email']);
            $checkUsername = $author->checkDuplicate("username", $dataAuthor['username']);

            if ($checkEmail) {
                $err['email'][] = "E-mail already registered";
            }
            if ($checkUsername) {
                $err['username'][] = "Username already registered";
            }

            if (preg_match("/[^a-zA-Z0-9._\s]/i", $dataReg['username'])) {
                $err['username'][] = "Only use alphanumeric, uppercase, lowercase, dot, and underscore";
            }
        }

        if (count($err) > 0) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => $err
            ], 400);
        }

        $dataReg['password'] = password_hash($dataReg['password'], PASSWORD_DEFAULT);


        $user_id = $user->insert(array_merge($dataReg, ["role" => "user"]));



        if ($author->insert(array_merge($dataAuthor, ["user_id" => $user_id]))) {
            return $this->json([
                "message" => "Success Register",
                "data" => $_POST
            ]);
        } else {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null
            ], 500);
        }
    }
}
