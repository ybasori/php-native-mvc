<?php

namespace App\Models;

use System\Model;
use System\Database as DB;
// use Ramsey\Uuid\Uuid;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

class User extends Model
{

    public $table = "users";
    private $db;

    function __construct()
    {
        parent::__construct();
        $this->db = new DB;
    }

    function getJoinAuthorAll($data, $debug = false)
    {
        $sort = $this->querySort($data['sort']);

        $limit = $this->queryPagination($data['pagination']);

        if (empty($data['where'])) {
            $data['where'] = [];
        }
        if (empty($data['orwhere'])) {
            $data['orwhere'] = [];
        }
        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);

        $selectArr = [];
        $selectArr[] = $this->table . ".id, " . $this->table . ".email, " . $this->table . ".role, " .  $this->table . ".created_at";
        $selectArr[] = "authors.username, authors.id as author_id";

        $joinArr = [];
        $joinArr[] = "LEFT JOIN authors ON authors.user_id = " . $this->table . ".id";

        $sql = "SELECT " . implode(", ", $selectArr) . " FROM " . $this->table . " " . implode(" ", $joinArr);

        $sql = "SELECT * FROM ($sql) tbl $where $sort $limit";

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetchAll();
    }

    function getJoinAuthor($data, $debug = false)
    {
        if (empty($data['where'])) {
            $data['where'] = [];
        }
        if (empty($data['orwhere'])) {
            $data['orwhere'] = [];
        }
        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);

        $selectArr = [];
        $selectArr[] = $this->table . ".id, " . $this->table . ".email, " . $this->table . ".role, " .  $this->table . ".created_at";
        $selectArr[] = "authors.username, authors.id as author_id";

        $joinArr = [];
        $joinArr[] = "LEFT JOIN authors ON authors.user_id = " . $this->table . ".id";

        $sql = "SELECT " . implode(", ", $selectArr) . " FROM " . $this->table . " " . implode(" ", $joinArr);

        $sql = "SELECT * FROM ($sql) tbl $where";

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    function getJoinAuthorTotal($data, $debug = false)
    {
        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);

        $selectArr = [];
        $selectArr[] = $this->table . ".email, " . $this->table . ".role, " .  $this->table . ".created_at";
        $selectArr[] = "authors.username";

        $joinArr = [];
        $joinArr[] = "LEFT JOIN authors ON authors.user_id = " . $this->table . ".id";

        $sql = "SELECT " . implode(", ", $selectArr) . " FROM " . $this->table . " " . implode(" ", $joinArr);

        $sql = "SELECT COUNT(*) as total FROM ($sql) tbl $where";

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    //     public function insertWithPassword($data)
    //     {
    //         $password = $data['password'];
    //         unset($data['password']);
    //         unset($data['confirmationPassword']);

    //         $uid = Uuid::uuid4();

    //         $data['uid'] = $uid;
    //         $data['created_at'] = date('Y-m-d H:i:s');

    //         $query = $this->db->query('INSERT INTO users (uid, username, email, created_at) VALUES(:uid, :username, :email, :created_at)');
    //         $query->execute($data);

    //         $getUserIdQuery = $this->db->query('SELECT * FROM users WHERE uid=:uid');
    //         $getUserIdQuery->execute([
    //             "uid" => $uid
    //         ]);
    //         $user = $getUserIdQuery->fetch();

    //         $queryInsertUserSignIn = $this->db->query('INSERT INTO user_sign_in_withs (uid, user_id, sign_in_id, sign_in_type, sign_in_email, sign_in_additional_data, created_at) VALUES(:uid, :user_id, :user_uid, :sign_in_type, :sign_in_email, :sign_in_additional_data, :created_at)');

    //         $queryInsertUserSignIn->execute([
    //             "uid" => Uuid::uuid4(),
    //             "user_id" => $user->id,
    //             "user_uid" => $uid,
    //             "sign_in_type" => "password",
    //             "sign_in_email" => $data['email'],
    //             "sign_in_additional_data" => json_encode((object) [
    //                 "password" => password_hash($password, PASSWORD_DEFAULT)
    //             ]),
    //             "created_at" => date('Y-m-d H:i:s')
    //         ]);

    //         return true;
    //     }

    //     public function loginWithPassword($data)
    //     {

    //         $query = null;

    //         if (filter_var($data["username"], FILTER_VALIDATE_EMAIL)) {
    //             $query = $this->db->query('SELECT * FROM users WHERE email = :username');
    //         } else {
    //             $query = $this->db->query('SELECT * FROM users WHERE username = :username');
    //         }

    //         $query->execute([
    //             "username" => $data['username']
    //         ]);

    //         $user = $query->fetch();

    //         if (!$user) {
    //             return [
    //                 "errors" => [
    //                     "username" => "Username / E-mail not registered."
    //                 ],
    //                 "message" => "User not registered!"
    //             ];
    //         }

    //         $querySignInWith = $this->db->query('SELECT * FROM user_sign_in_withs WHERE user_id = :userId AND sign_in_type = :signInType');

    //         $querySignInWith->execute([
    //             "userId" => $user->id,
    //             "signInType" => "password"
    //         ]);

    //         $userSignInWith = $querySignInWith->fetch();

    //         $addiData = json_decode($userSignInWith->sign_in_additional_data);


    //         if (password_verify($data['password'], $addiData->password)) {

    //             $iat = time();
    //             $exp = $iat + (60 * 60);
    //             $key = $_ENV['JWT_KEY'];
    //             $payload = array(
    //                 "iss" => $_ENV['APP_URL'],
    //                 "aud" => $_ENV['APP_URL'],
    //                 "iat" => $iat,
    //                 "exp" => $exp,
    //                 "user" => $user
    //             );

    //             $jwt = JWT::encode($payload, $key, 'HS256');

    //             unset($user->id);


    //             return [
    //                 "errors" => null,
    //                 "data" => array_merge((array)$user, [
    //                     "token" => $jwt,
    //                     "expires" => $exp
    //                 ]),
    //                 "message" => "Succesfully logged in."
    //             ];
    //         } else {
    //             return [
    //                 "errors" => [
    //                     "password" => "Password incorrect."
    //                 ],
    //                 "message" => "Password incorrect."
    //             ];
    //         }
    //     }

    //     public function validateToken($token)
    //     {
    //         return JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
    //     }
}
