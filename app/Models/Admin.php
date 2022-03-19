<?php

namespace App\Models;

use System\Database as DB;
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Admin
{

    private $db;

    function __construct()
    {
        $this->db = new DB;
    }

    public function insertWithPassword($data)
    {
        $password = $data['password'];
        unset($data['password']);
        unset($data['confirmationPassword']);

        $uid = Uuid::uuid4();

        $data['uid'] = $uid;
        $data['created_at'] = date('Y-m-d H:i:s');

        $query = $this->db->query('INSERT INTO admins (uid, username, email, created_at) VALUES(:uid, :username, :email, :created_at)');
        $query->execute($data);

        $getUserIdQuery = $this->db->query('SELECT * FROM admins WHERE uid=:uid');
        $getUserIdQuery->execute([
            "uid" => $uid
        ]);
        $user = $getUserIdQuery->fetch();

        $queryInsertUserSignIn = $this->db->query('INSERT INTO admin_sign_in_withs (uid, admin_id, sign_in_id, sign_in_type, sign_in_email, sign_in_additional_data, created_at) VALUES(:uid, :admin_id, :admin_uid, :sign_in_type, :sign_in_email, :sign_in_additional_data, :created_at)');

        $queryInsertUserSignIn->execute([
            "uid" => Uuid::uuid4(),
            "admin_id" => $user->id,
            "admin_uid" => $uid,
            "sign_in_type" => "password",
            "sign_in_email" => $data['email'],
            "sign_in_additional_data" => json_encode((object) [
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ]),
            "created_at" => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function loginWithPassword($data)
    {

        $query = null;

        if (filter_var($data["username"], FILTER_VALIDATE_EMAIL)) {
            $query = $this->db->query('SELECT * FROM admins WHERE email = :username');
        } else {
            $query = $this->db->query('SELECT * FROM admins WHERE username = :username');
        }

        $query->execute([
            "username" => $data['username']
        ]);

        $user = $query->fetch();

        if (!$user) {
            return [
                "errors" => [
                    "username" => "Username / E-mail not registered."
                ],
                "message" => "User not registered!"
            ];
        }

        $querySignInWith = $this->db->query('SELECT * FROM admin_sign_in_withs WHERE admin_id = :adminId AND sign_in_type = :signInType');

        $querySignInWith->execute([
            "adminId" => $user->id,
            "signInType" => "password"
        ]);

        $userSignInWith = $querySignInWith->fetch();

        $addiData = json_decode($userSignInWith->sign_in_additional_data);


        if (password_verify($data['password'], $addiData->password)) {

            $iat = time();
            $exp = $iat + (60 * 60);
            $key = $_ENV['JWT_KEY'];
            $payload = array(
                "iss" => $_ENV['APP_URL'],
                "aud" => $_ENV['APP_URL'],
                "iat" => $iat,
                "exp" => $exp,
                "admin" => $user
            );

            $jwt = JWT::encode($payload, $key, 'HS256');

            unset($user->id);


            return [
                "errors" => null,
                "data" => array_merge((array)$user, [
                    "token" => $jwt,
                    "expires" => $exp
                ]),
                "message" => "Succesfully logged in."
            ];
        } else {
            return [
                "errors" => [
                    "password" => "Password incorrect."
                ],
                "message" => "Password incorrect."
            ];
        }
    }

    public function validateToken($token)
    {
        return JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
    }
}
