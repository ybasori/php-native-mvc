<?php

namespace App\Controllers\JSON;

use App\Controllers\Controller;
use App\Models\Author;
use App\Models\DataItem;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {

        $pagination = [];
        $sort = !empty($_GET['sort']) ? $_GET['sort'] : [];
        if (!empty($_GET['page']) && !empty($_GET['limit'])) {
            $pagination = array_merge($pagination, [
                "page" => $_GET['page'],
                "limit" => $_GET['limit'],
            ]);
        }
        if (!empty($_GET['page']) && empty($_GET['limit'])) {
            $pagination = array_merge($pagination, [
                "page" => $_GET['page'],
                "limit" => 10,
            ]);
        }
        if (empty($_GET['page']) && !empty($_GET['limit'])) {
            $pagination = array_merge($pagination, [
                "page" => 1,
                "limit" => $_GET['limit'],
            ]);
        }

        $sort = count($sort) > 0 ? ["sort" => $sort] : [];
        $pagination = count($pagination) > 0 ? ["pagination" => $pagination] : [];
        $searchArr = [];
        if (!empty($_GET['search'])) {
            foreach ($_GET['search'] as $key => $value) {
                $searchArr[] = [$key, "LIKE", $value];
            }
        }


        $clause = [
            "orwhere" => $searchArr
        ];

        $user = new User;
        $data_user = $user->getJoinAuthorAll(
            array_merge(
                array_merge(
                    $clause,
                    $pagination
                ),
                $sort
            ),
            // true
        );

        $count = $user->getJoinAuthorTotal($clause);

        return $this->json([
            "message" => "Data found",
            "data" => [
                "data" => $data_user,
                "total" => $count->total
            ]
        ], 200);
    }

    public function show($params)
    {
        $user = new User;
        $data_user = $user->getJoinAuthor(
            [
                "where" => [
                    ['id', "=", $params->id]
                ]
            ]
        );

        if ($data_user) {
            return $this->json([
                "message" => "Data found",
                "data" => $data_user
            ], 200);
        } else {
            return $this->json([
                "message" => "Data not found",
                "data" => null
            ], 404);
        }
    }

    public function update($params)
    {
        parse_str(file_get_contents('php://input'), $_PUT);

        $user = new User;
        $author = new Author;
        $err = [];
        $dataUpdate = $_PUT;
        if (empty($dataUpdate['email'])) {
            $err['email'][] = "E-mail is required!";
        }
        if (empty($dataUpdate['username'])) {
            $err['username'][] = "Name is required!";
        }

        $data_user = $user->getJoinAuthor(
            [
                "where" => [
                    ['id', "=", $params->id]
                ]
            ]
        );
        if ($data_user) {

            $checkEmail = $user->checkDuplicate("email", $dataUpdate['email']);
            if (!empty($checkEmail) && $checkEmail->id !== (int) $params->id) {
                $err['email'][] = "E-mail is already exist!";
            }
            $checkUsername = $author->checkDuplicate("username", $dataUpdate['username']);
            if (!empty($checkUsername) && $checkUsername->id !== $data_user->author_id) {
                $err['username'][] = "Username is already exist!";
            }
            if (preg_match("/[^a-zA-Z0-9._\s]/i", $dataUpdate['username']) || preg_match("/\s/i", $dataUpdate['username'])) {
                $err['username'][] = "Only use alphanumeric, uppercase, lowercase, dot, and underscore";
            }
            if (count($err) > 0) {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => $err
                ], 400);
            }

            $setuser = [
                "email" => $dataUpdate['email'],
                "role" => $dataUpdate['role']
            ];

            if (!empty($dataUpdate['password'])) {
                $setuser['password'] = password_hash($dataUpdate['password'], PASSWORD_DEFAULT);
            }

            $user->update([
                "set" => $setuser,
                "where" => [
                    ["id", "=", $params->id]
                ]
            ]);
            $author->update([
                "set" => [
                    "username" => $dataUpdate['username']
                ],
                "where" => [
                    ["id", "=", $data_user->author_id]
                ]
            ]);
            return $this->json([
                "message" => "Success update!",
                "data" => $dataUpdate,
            ], 200);
        }
        return $this->json([
            "message" => "Data not found!",
            "data" => null,
        ], 404);
    }

    public function delete($params)
    {
        $me = $this->getUser();


        $user = new User;

        $u = $user->getJoinAuthor([
            "where" => [
                ["id", "=", $params->id]
            ]
        ]);

        if ($me->author->id != $u->author_id) {

            $dataItem = new DataItem;
            $dataItem->delete([
                "where" => [
                    ["author_id_created_by", "=", $u->author_id]
                ]
            ]);
            $author = new Author;
            $author->delete([
                "where" => [
                    ["id", "=", $u->author_id]
                ]
            ]);
            $user->delete([
                "where" => [
                    ["id", "=", $params->id]
                ]
            ]);

            return $this->json([
                "message" => "Success deleted",
                "data" => $u
            ], 200);
        }
        return $this->json([
            "message" => "You cannot delete yourself",
            "data" => null
        ], 400);
    }
}
