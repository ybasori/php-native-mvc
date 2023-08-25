<?php

namespace App\Controllers;

use App\Models\DataField;
use App\Models\DataItem;
use App\Models\Path;

class JSONController extends Controller
{

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
            return $this->json(["message" => "not found"], 404);
        } else {
            if (count($cur['params']) > 0) {
                $dataItem = new DataItem;
                $data = $dataItem->get([
                    "where" => [
                        ["path_id", "=", $cur['path_id']],
                        ["slug", "=", $cur['params']['slug']]
                    ]
                ]);
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
            } else {
                $dataItem = new DataItem;
                $data = $dataItem->getAll([
                    "where" => [
                        ["path_id", "=", $cur['path_id']]
                    ]
                ]);
            }
            return $this->json(["message" => "data found", "data" => $data]);
        }
    }
}
