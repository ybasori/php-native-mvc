<?php

namespace App\Models;

use System\Model;
use System\Database as DB;

class DataItem extends Model
{

    public $table = "data_items";
    private $db;

    function __construct()
    {
        parent::__construct();
        $this->db = new DB;
    }

    function getJoinFieldAll($fields, $data)
    {

        if (empty($data['sort'])) {
            $data['sort'] = [["created_at", "ASC"]];
        }

        $sort = "";
        $sortArr = [];
        foreach ($data['sort'] as $s) {
            $sortArr[] = implode(" ", $s);
        }

        if (count($sortArr) > 0) {
            $sort = "ORDER BY " . implode(", ", $sortArr);
        }


        $limit = "";
        if (!empty($data['pagination'])) {
            $offset = ($data['pagination']['page'] - 1) * $data['pagination']['limit'];
            $limit = "LIMIT $offset," . $data['pagination']['limit'];
        }

        $where = "";
        $whereArr = [];

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $whereArr[] = " " . $item[0] . " " . $item[1] . " " . $item[2];
            } else {
                $item[2] = "'$item[2]'";
                $whereArr[] = " " . implode(" ", $item);
            }
        }
        if (count($whereArr) > 0) {
            $where = "WHERE " . implode(" AND ", $whereArr);
        }

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $sql = "SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr) . " $where $sort $limit";

        // print_r($sql);
        // die;

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetchAll();
    }


    function getJoinField($fields, $data)
    {

        $where = "";
        $whereArr = [];

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $whereArr[] = " " . $item[0] . " " . $item[1] . " " . $item[2];
            } else {
                $item[2] = "'$item[2]'";
                $whereArr[] = " " . implode(" ", $item);
            }
        }
        if (count($whereArr) > 0) {
            $where = "WHERE " . implode(" AND ", $whereArr);
        }

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $sql = "SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr) . " $where";

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    function getTotalFieldJoin($fields, $data)
    {

        $where = "";
        $whereArr = [];

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $whereArr[] = " " . $item[0] . " " . $item[1] . " " . $item[2];
            } else {
                $item[2] = "'$item[2]'";
                $whereArr[] = " " . implode(" ", $item);
            }
        }
        if (count($whereArr) > 0) {
            $where = "WHERE " . implode(" AND ", $whereArr);
        }

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $sql = "SELECT COUNT(*) as total FROM (SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr) . " $where) tbl_main LIMIT 1";

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }
}
