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

    function getJoinPathByPath($prefix, $path, $debug = false)
    {
        $sql = "SELECT * FROM (SELECT CONCAT('" . $prefix . "', CONCAT(paths.full_path, CONCAT('/', " . $this->table . ".slug))) as pathname, " . $this->table . ".id, " . $this->table . ".path_id, paths.full_path, paths.privacy  FROM " . $this->table . " JOIN paths ON " . $this->table . ".path_id = paths.id) tbl WHERE pathname = '" . $path . "'";


        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    function getJoinFieldAll($fields, $data, $debug = false)
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

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $joinAuthor = "LEFT JOIN (SELECT * FROM authors) tbl_author ON " . $this->table . ".author_id_created_by = tbl_author.id";
        $joinArr[] = $joinAuthor;
        $selectArr[] = "tbl_author.username as created_by";

        $sql = "SELECT " . implode(",", $selectArr) . " FROM $this->table " . implode(" ", $joinArr);

        $sql = "SELECT * FROM ($sql) tbl $where $sort $limit";

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetchAll();
    }


    function getJoinField($fields, $data, $debug = false)
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

        $joinArr = [];
        $selectArr = [$this->table . ".*"];
        foreach ($fields as $field) {
            $joinArr[] = "LEFT JOIN (SELECT " . ($field->type == "number" ? " cast(value as int)" : "value") . " as " . $field->name . ", data_item_id FROM data_fields WHERE field_form_id=" . $field->id . ") tbl_" . $field->name . " ON " . $this->table . ".id = tbl_" . $field->name . ".data_item_id";
            $selectArr[] = "tbl_" . $field->name . "." . $field->name;
        }

        $joinAuthor = "LEFT JOIN (SELECT username, id FROM authors) tbl_author ON " . $this->table . ".author_id_created_by = tbl_author.id";
        $joinArr[] = $joinAuthor;
        $selectArr[] = "tbl_author.username as created_by";

        $sql = "SELECT " . implode(",", $selectArr) . " FROM " . $this->table . " " . implode(" ", $joinArr);

        $sql = "SELECT * FROM (" . $sql . ") tbl_main " . $where;

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    function getTotalFieldJoin($fields, $data)
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
