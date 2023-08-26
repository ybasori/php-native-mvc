<?php

namespace App\Controllers;

use App\Models\DataField;
use App\Models\DataItem;
use App\Models\FieldForm;
use App\Models\Path;

class JSONController extends Controller
{

    private function getDetail($path_id, $slug)
    {
        $dataItem = new DataItem;
        $data = $dataItem->get([
            "where" => [
                ["path_id", "=", $path_id],
                ["slug", "=", $slug]
            ]
        ]);
        if ($data) {
            $dataField = new DataField;
            $fields = $dataField->getAll([
                "where" => [
                    ["data_item_id", "=", $data->id]
                ]
            ]);

            $dt = [];

            foreach ($fields as $value) {
                $dt[$value->name] = $value->value;
            }

            $data->fields = (object) $dt;
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
            if (count($cur['params']) > 0) {

                $data = $this->getDetail($cur['path_id'], $cur['params']['slug']);
                if (!$data) {
                    return $this->json([
                        "message" => "not found",
                        "data" => null
                    ], 404);
                }
            } else {
                $dataItem = new DataItem;
                $data = $dataItem->getAll([
                    "where" => [
                        ["path_id", "=", $cur['path_id']]
                    ]
                ]);
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
            $dataItem['path_id'] = $cur['path_id'];
            unset($dataItem['slug']);
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
            $lastdt = $di->checkExistingSlug($dataItem['slug']);

            if ($lastdt) {
                $dataItem['slug'] = $dataItem['slug'] . "-" . ($lastdt->number + 1);
            }

            $id = $di->insert($dataItem);

            if (!empty($_POST['field'])) {
                $dataField = $_POST['field'];
                $ff = new FieldForm;
                $fields = $ff->getAll([
                    "where" => [
                        ["path_id", "=", $cur['path_id']]
                    ]
                ]);

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
            $data = $this->getDetail($cur['path_id'], $dataItem['slug']);
            return $this->json([
                "message" => "data found",
                "data" => $data
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
