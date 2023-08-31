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

    function getJoinFieldAll($fields, $data, $debug = false)
    {

        $sort = $this->querySort($data['sort']);

        $limit = $this->queryPagination($data['pagination']);

        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $joinAuthor = "LEFT JOIN (SELECT username, id FROM authors) tbl_author ON " . $this->table . ".author_id_created_by = tbl_author.id";
        $joinArr[] = $joinAuthor;
        $selectArr[] = "tbl_author.username as created_by";

        $sql = "SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr) . " $where $sort $limit";

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetchAll();
    }


    function getJoinField($fields, $data)
    {


        $where = $this->queryWhereClause($data['where']);

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $joinAuthor = "LEFT JOIN (SELECT username, id FROM authors) tbl_author ON " . $this->table . ".author_id_created_by = tbl_author.id";
        $joinArr[] = $joinAuthor;
        $selectArr[] = "tbl_author.username as created_by";


        $sql = "SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr) . " $where";

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    function getTotalFieldJoin($fields, $data)
    {

        $where = $this->queryWhereClause($data['where']);

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $joinAuthor = "LEFT JOIN (SELECT username, id FROM authors) tbl_author ON " . $this->table . ".author_id_created_by = tbl_author.id";
        $joinArr[] = $joinAuthor;
        $selectArr[] = "tbl_author.username as created_by";

        $sql = "SELECT COUNT(*) as total FROM (SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr) . " $where) tbl_main LIMIT 1";

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }
}
