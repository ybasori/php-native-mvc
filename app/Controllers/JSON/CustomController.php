<?php

namespace App\Controllers\JSON;

use App\Models\DataField;
use App\Models\DataItem;
use App\Models\FieldForm;
use App\Models\Path;
use App\Controllers\Controller;

class CustomController extends Controller
{
    private $path = "/json/custom";
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
                    "where" => [
                        ["path_id", "=", $cur['path_id']]
                    ],
                    "orwhere" => $searchArr
                ];
                $data = $dataItem->getJoinFieldAll($fs, array_merge(array_merge($clause, $pagination), $sort));

                $count = $dataItem->getTotalFieldJoin($fs, $clause);

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

        $cur = $this->getSelectedPath($this->path);
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
        $cur = $this->getSelectedPath($this->path);
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
        $cur = $this->getSelectedPath($this->path);
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
}
