<?php

namespace App\Controllers\JSON;

use App\Models\DataField;
use App\Models\DataItem;
use App\Models\FieldForm;
use App\Models\Path;
use App\Controllers\Controller;

class CustomController extends Controller
{
    private $path = "/json/v1/custom";
    private function getDetail($path_id, $slug, $fields)
    {

        $dataItem = new DataItem;
        $data = $dataItem->getJoinField($fields, [
            "where" => [
                ["path_id", "=", $path_id],
                ["slug", "=", $slug]
            ]
        ]);
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

        $cur = $this->getSelectedPath($this->path);

        $fields = new FieldForm;
        if ($cur['type'] === "index") {

            $authorQuery = [];
            if ($cur['data']['privacy'] == "only-me" || $cur['data']['privacy'] == "only-logged-in-user") {
                $user = $this->getUser();
                if ($user === "expired") {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => [
                            'token' => ["Token is expired!"]
                        ]
                    ], 401);
                } else if ($user === "required") {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => [
                            'token' => ["Token is required!"]
                        ]
                    ], 401);
                } else {
                    if ($cur['data']['privacy'] == "only-me") {
                        $authorQuery[] = ["author_id_created_by", "=", $user->author->id];
                    }
                }
            }


            $fs = $fields->getAll([
                "where" => [
                    ["path_id", "=", $cur['data']['path_id']]
                ]
            ]);

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
            $dataItem = new DataItem;
            $clause = [
                "where" => array_merge(
                    [
                        ["path_id", "=", $cur['data']['path_id']]
                    ],
                    $authorQuery
                ),
                "orwhere" => $searchArr
            ];
            $data = $dataItem->getJoinFieldAll(
                $fs,
                array_merge(
                    array_merge(
                        $clause,
                        $pagination
                    ),
                    $sort
                ),
                // true
            );

            $count = $dataItem->getTotalFieldJoin($fs, $clause);

            foreach ($data as $key => $dt) {
                unset($data[$key]->author_id_created_by);
                unset($data[$key]->path_id);
                unset($data[$key]->id);
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
        } else if ($cur['type'] === "detail") {
            $authorQuery = [];
            if ($cur['data']['privacy'] == "only-me" || $cur['data']['privacy'] == "only-logged-in-user") {
                $user = $this->getUser();
                if ($user === "expired") {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => [
                            'token' => ["Token is expired!"]
                        ]
                    ], 401);
                } else if ($user === "required") {
                    return $this->json([
                        "message" => "Something went wrong!",
                        "data" => null,
                        "errors" => [
                            'token' => ["Token is required!"]
                        ]
                    ], 401);
                } else {
                    if ($cur['data']['privacy'] == "only-me") {
                        $authorQuery[] = ["author_id_created_by", "=", $user->author->id];
                    }
                }
            }
            $dataItem = new DataItem;
            $fs = $fields->getAll([
                "where" => [
                    ["path_id", "=", $cur['data']['path_id']]
                ]
            ]);

            $data = $dataItem->getJoinField($fs, [
                "where" => array_merge([
                    ["id", "=", $cur['data']['data_item_id']],
                ], $authorQuery)
            ]);
            unset($data->author_id_created_by);
            unset($data->path_id);
            unset($data->id);
        } else {
            return $this->json([
                "message" => "not found",
                "data" => null
            ], 404);
        }

        return $this->json([
            "message" => "data found",
            "data" => $data,
        ]);
    }

    public function store()
    {

        $cur = $this->getSelectedPath($this->path);

        $fields = new FieldForm;
        if ($cur['type'] === "index") {
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
            $dataItem['path_id'] = $cur['data']['path_id'];
            unset($dataItem['field']);

            $dataItem['slug'] = preg_replace("/[^a-zA-Z0-9-_\s]/i", "", $dataItem['title']);
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
                    ["path_id", "=", $cur['data']['path_id']]
                ]
            ]);
            if (!empty($_POST['field'])) {
                $dataField = $_POST['field'];

                $df = new DataField;
                foreach ($fields as $field) {

                    $df->insert([
                        "data_item_id" => $id,
                        "field_form_id" => $field->id,
                        "path_id" => $cur['data']['path_id'],
                        "name" => $field->name,
                        "value" => $dataField[$field->name]
                    ]);
                }
            }
            $data = $this->getDetail($cur['data']['path_id'], $dataItem['slug'], $fields);
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
        $cur = $this->getSelectedPath($this->path);
        if ($cur['type'] == "detail") {
            $dataItem = $_PUT;
            unset($dataItem['slug']);
            unset($dataItem['field']);

            $dis = new DataItem;
            $di = $dis->get([
                "where" => [
                    ["id", "=", $cur['data']['data_item_id']]
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
                    ["id", "=", $cur['data']['data_item_id']]
                ]
            ]);
            if (!empty($_PUT['field'])) {
                $dataField = $_PUT['field'];
                foreach ($dataField as $key => $value) {
                    $dfs = new DataField;

                    $df = $dfs->get([
                        "where" => [
                            ["path_id", "=", $cur['data']['path_id']],
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
                                ["path_id", "=", $cur['data']['path_id']],
                                ["data_item_id", "=", $di->id],
                                ['name', "=", $key]
                            ]
                        ]);
                    } else {

                        $ff = new FieldForm;
                        $field = $ff->get([
                            "where" => [
                                ["name", "=", $key],
                                ["path_id", "=", $cur['data']['path_id']]
                            ]
                        ]);
                        if ($ff) {
                            $dfs->insert([
                                "path_id" => $cur['data']['path_id'],
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
        $cur = $this->getSelectedPath($this->path);
        if ($cur['type'] === "detail") {

            $dataItem = new DataItem;
            $data = $dataItem->get([
                "where" => [
                    ["id", "=", $cur['data']['data_item_id']]
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
                    ["data_item_id", "=", $cur['data']['data_item_id']]
                ]
            ]);
            $dataItem->delete([
                "where" => [
                    ["id", "=", $cur['data']['data_item_id']],
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
