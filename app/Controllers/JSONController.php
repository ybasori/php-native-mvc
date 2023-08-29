<?php

namespace App\Controllers;

use App\Models\Author;
use App\Models\DataField;
use App\Models\DataItem;
use App\Models\FieldForm;
use App\Models\Path;
use App\Models\User;
use Firebase\JWT\JWT;

class JSONController extends Controller
{

    private function getDetail($path_id, $slug, $fields)
    {

        $dataItem = new DataItem;
        // $data = $dataItem->get([
        //     "where" => [
        //         ["path_id", "=", $path_id],
        //         ["slug", "=", $slug]
        //     ]
        // ]);
        $data = $dataItem->getJoinField($fields, [
            "where" => [
                ["path_id", "=", $path_id],
                ["slug", "=", $slug]
            ]
        ]);
        if ($data) {

            $author = new Author;
            $a = $author->get([
                "where" => [
                    ["id", "=", $data->author_id_created_by]
                ],
            ]);
            unset($a->id);
            unset($a->user_id);
            unset($data->id);
            unset($data->path_id);
            unset($data->author_id_created_by);
            $data->created_by = $a;
        }
        return $data;
    }

    public function index()
    {
        $path = new Path;
        $data_path = $path->getAll([]);

        return $this->json(["data" => $data_path]);
    }

    public function show()
    {
        $cur = $this->getSelectedPath("/json");


        if ($cur['matched'] === false) {
            return $this->json([
                "message" => "not found",
                "data" => null
            ], 404);
        } else {

            $fields = new FieldForm;
            $fs = $fields->getAll([
                "where" => [
                    ["path_id", "=", $cur['path_id']]
                ]
            ]);
            if (count($cur['params']) > 0) {

                $data = $this->getDetail($cur['path_id'], $cur['params']['slug'], $fs);
                if (!$data) {
                    return $this->json([
                        "message" => "not found",
                        "data" => null
                    ], 404);
                }
            } else {
                $pagination = [];
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

                $pagination = count($pagination) > 0 ? ["pagination" => $pagination] : [];
                $dataItem = new DataItem;
                $clause = [
                    "where" => [
                        ["path_id", "=", $cur['path_id']]
                    ]
                ];
                $data = $dataItem->getJoinFieldAll($fs, array_merge($clause, $pagination));

                $count = $dataItem->getTotalFieldJoin($fs, $clause);

                $authorids = [];
                foreach ($data as $key => $dt) {
                    unset($data[$key]->id);
                    unset($data[$key]->path_id);
                    if (!in_array($dt->author_id_created_by, $authorids)) {
                        $authorids[] = "'" . $dt->author_id_created_by . "'";
                    }
                }
                if (count($authorids) > 0) {
                    $author = new Author;
                    $as = $author->getAll([
                        "where" => [
                            ["id", "IN", "(" . implode(", ", $authorids) . ")", true]
                        ],
                    ]);

                    foreach ($data as $key => $dt) {
                        foreach ($as as $a) {
                            if ($dt->author_id_created_by == $a->id) {
                                unset($data[$key]->author_id_created_by);
                                unset($a->user_id);
                                unset($a->id);
                                $data[$key]->created_by = $a;
                            }
                        }
                    }
                }
                $data = [
                    "data" => $data,
                    "total" => $count->total
                ];
                if (!is_array($data)) {
                    return $this->json([
                        "message" => "not found",
                        "data" => null
                    ], 404);
                }
            }
            return $this->json([
                "message" => "data found",
                "data" => $data,
            ]);
        }
    }

    public function store()
    {

        $cur = $this->getSelectedPath("/json");
        if ($cur['matched']) {
            $dataItem = $_POST;

            $err = [];
            $user = $this->getUser();
            if ($user === "expired") {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => [
                        'token' => ["Token is expired!"]
                    ]
                ], 401);
            }
            if ($user === "required") {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => [
                        'token' => ["Token is required!"]
                    ]
                ], 401);
            }

            if (empty($dataItem['title'])) {
                $err['title'][] = "Title is required!";
            }
            if (count($err) > 0) {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => $err
                ], 400);
            }

            $dataItem['author_id_created_by'] = $user->author->id;
            $dataItem['path_id'] = $cur['path_id'];
            unset($dataItem['field']);

            $dataItem['slug'] = preg_replace("/[^a-zA-Z0-9-_\s]/i", "", $dataItem['title']);
            $dataItem['slug'] = preg_replace("/\s/i", "-", $dataItem['slug']);
            $dataItem['slug'] = preg_replace("/\s/i", "-", $dataItem['slug']);

            $lastchar = substr($dataItem['slug'], -1);
            if ($lastchar == "-" || $lastchar == "_") {
                $dataItem['slug'] = substr_replace($dataItem['slug'], "", -1);
            }

            $dataItem['slug'] = strtolower($dataItem['slug']);

            $di = new DataItem;
            $lastdt = $di->checkDuplicate("slug", $dataItem['slug']);

            if ($lastdt) {
                $dataItem['slug'] = $dataItem['slug'] . "-" . ($lastdt->number + 1);
            }

            $id = $di->insert($dataItem);

            $ff = new FieldForm;
            $fields = $ff->getAll([
                "where" => [
                    ["path_id", "=", $cur['path_id']]
                ]
            ]);
            if (!empty($_POST['field'])) {
                $dataField = $_POST['field'];

                $df = new DataField;
                foreach ($fields as $field) {

                    $df->insert([
                        "data_item_id" => $id,
                        "field_form_id" => $field->id,
                        "path_id" => $cur['path_id'],
                        "name" => $field->name,
                        "value" => $dataField[$field->name]
                    ]);
                }
            }
            $data = $this->getDetail($cur['path_id'], $dataItem['slug'], $fields);
            return $this->json([
                "message" => "data found",
                "data" => $data,
            ], 200);
        } else {
            return $this->json([
                "message" => "not found",
                "data" => null
            ], 404);
        }
    }

    public function update()
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        $cur = $this->getSelectedPath("/json");
        if ($cur['matched']) {
            $dataItem = $_PUT;
            unset($dataItem['slug']);
            unset($dataItem['field']);

            $dis = new DataItem;
            $di = $dis->get([
                "where" => [
                    ["slug", "=", $cur['params']['slug']]
                ]
            ]);

            $err = [];
            $user = $this->getUser();

            if ($user === "expired") {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => [
                        'token' => ["Token is expired!"]
                    ]
                ], 401);
            }
            if ($user === "required") {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => [
                        'token' => ["Token is required!"]
                    ]
                ], 401);
            }
            if ($user->author->id !== $di->author_id_created_by) {
                $err['token'][] = "Token is invalid!";
            }
            if (empty($dataItem['title'])) {
                $err['title'][] = "Title is required!";
            }

            if (count($err) > 0) {
                return $this->json([
                    "message" => "Something went wrong!",
                    "data" => null,
                    "errors" => $err
                ], 400);
            }

            $dis->update([
                "set" => $dataItem,
                "where" => [
                    ["slug", "=", $cur['params']['slug']]
                ]
            ]);
            if (!empty($_PUT['field'])) {
                $dataField = $_PUT['field'];
                foreach ($dataField as $key => $value) {
                    $dfs = new DataField;

                    $df = $dfs->get([
                        "where" => [
                            ["path_id", "=", $cur['path_id']],
                            ["data_item_id", "=", $di->id],
                            ['name', "=", $key]
                        ]
                    ]);
                    if ($df) {
                        $dfs->update([
                            "set" => [
                                "value" => $value
                            ],
                            "where" => [
                                ["path_id", "=", $cur['path_id']],
                                ["data_item_id", "=", $di->id],
                                ['name', "=", $key]
                            ]
                        ]);
                    } else {

                        $ff = new FieldForm;
                        $field = $ff->get([
                            "where" => [
                                ["name", "=", $key],
                                ["path_id", "=", $cur['path_id']]
                            ]
                        ]);
                        if ($ff) {
                            $dfs->insert([
                                "path_id" => $cur['path_id'],
                                "data_item_id" => $di->id,
                                'name' => $key,
                                "value" => $value,
                                "field_form_id" => $field->id
                            ]);
                        }
                    }
                }
            }
            return $this->json(["message" => "Changes saved", "data" => $_PUT]);
        } else {
            return $this->json([
                "message" => "not found",
                "data" => null
            ], 404);
        }
    }

    public function delete()
    {
        $cur = $this->getSelectedPath("/json");
        if ($cur['matched'] === false) {
            return $this->json([
                "message" => "not found",
                "data" => null
            ], 404);
        } else {
            if (count($cur['params']) > 0) {

                $dataItem = new DataItem;
                $data = $dataItem->get([
                    "where" => [
                        ["path_id", "=", $cur['path_id']],
                        ["slug", "=", $cur['params']['slug']]
                    ]
                ]);
                if (!$data) {
                    return $this->json([
                        "message" => "not found",
                        "data" => null
                    ], 404);
                }

                $err = [];
                $user = $this->getUser();

                if ($user === "expired") {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => [
                            'token' => ["Token is expired!"]
                        ]
                    ], 401);
                }
                if ($user === "required") {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => [
                            'token' => ["Token is required!"]
                        ]
                    ], 401);
                }
                if ($user->author->id !== $data->author_id_created_by) {
                    $err['token'][] = "Token is invalid!";
                }

                if (count($err) > 0) {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => $err
                    ], 400);
                }
                $dataField = new DataField;
                $dataField->delete([
                    "where" => [
                        ["data_item_id", "=", $data->id]
                    ]
                ]);
                $dataItem->delete([
                    "where" => [
                        ["path_id", "=", $cur['path_id']],
                        ["slug", "=", $cur['params']['slug']]
                    ]
                ]);
                return $this->json([
                    "message" => "data found",
                    "data" => $data,
                ]);
            } else {
                return $this->json([
                    "message" => "not found",
                    "data" => null
                ], 404);
            }
        }
    }

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

    public function author($params)
    {
        $author = new Author;

        $a = $author->get([
            "where" => [
                ["username", "=", $params->username]
            ]
        ]);

        if ($a) {
            unset($a->id);
            unset($a->user_id);
            return $this->json([
                "message" => "Data found",
                "data" => $a,
            ], 200);
        }
        return $this->json([
            "message" => "Data not found",
            "data" => null,
        ], 404);
    }
}
