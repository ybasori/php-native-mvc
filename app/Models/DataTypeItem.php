<?php

namespace App\Models;

use System\Database as DB;

class DataTypeItem
{

    private $db;

    function __construct()
    {
        $this->db = new DB;
    }

    public function getItem($data)
    {
        $where = "";

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $where .= " " . implode(" ", $item);
            } else {
                $item[2] = "'$item[2]'";
                $where .= " " . implode(" ", $item);
            }
        }
        if ($where != "") {
            $where = "WHERE $where";
        }

        $query = $this->db->query("SELECT * FROM data_type_items $where");
        $query->execute();

        return $query->fetch();
    }

    public function getAll($data)
    {
        $limit = $data['pagination']['limit'];
        $offset = ($data['pagination']['page'] - 1) * $limit;

        $where = "";

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $where .= " " . implode(" ", $item);
            } else {
                $item[2] = "'$item[2]'";
                $where .= " " . implode(" ", $item);
            }
        }
        if ($where != "") {
            $where = "WHERE $where";
        }


        $query = $this->db->query("SELECT * FROM data_type_items $where LIMIT $offset,$limit");
        $query->execute();

        return $query->fetchAll();
    }

    public function getTotal($data)
    {
        $where = "";

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $where .= " " . implode(" ", $item);
            } else {
                $item[2] = "'$item[2]'";
                $where .= " " . implode(" ", $item);
            }
        }
        if ($where != "") {
            $where = "WHERE $where";
        }
        $query = $this->db->query("SELECT COUNT(*) as total FROM data_type_items $where");
        $query->execute();

        return $query->fetch();
    }
}
