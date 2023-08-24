<?php

namespace System;

use Exception;
use System\Database as DB;

class Model
{

    private $db;
    public $table = "";

    function __construct()
    {
        $this->db = new DB;
    }

    public function get($data)
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

        $query = $this->db->query("SELECT * FROM $this->table $where");
        $query->execute();

        return $query->fetch();
    }

    public function getAll($data)
    {
        $limit = "";
        if (!empty($data['pagination'])) {
            $offset = ($data['pagination']['page'] - 1) * $data['pagination']['limit'];
            $limit = "LIMIT $offset,$data[pagination][limit]";
        }

        $where = "";
        if (!empty($data['where'])) {
            foreach ($data['where'] as $item) {
                if (!empty($item[3]) && $item[3] == true) {
                    $where .= " " . implode(" ", $item);
                } else {
                    $item[2] = "'$item[2]'";
                    $where .= " " . implode(" ", $item);
                }
            }
            if ($where != "") {
                $where = "WHERE $where";
            }
        }

        $query = $this->db->query("SELECT * FROM $this->table $where $limit");
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

        $query = $this->db->query("SELECT COUNT(*) as total FROM $this->table $where");
        $query->execute();

        return $query->fetch();
    }

    public function insert($data)
    {

        $label = "";
        $value = "";
        $data = array_merge($data, [
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
        foreach ($data as $key => $dt) {
            if ($label != "") {
                $label .= ", ";
            }
            $label .= $key;

            if ($value != "") {
                $value .= ", ";
            }
            $value .= "'$dt'";
        }


        $query = $this->db->query("INSERT INTO $this->table ($label) VALUES($value)");
        $query->execute();

        return $this->db->lastInsertedId();
    }

    public function delete($data)
    {

        try {
            $where = "";
            if (!empty($data['where'])) {
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
            }

            $query = $this->db->query("DELETE FROM $this->table $where");
            return $query->execute();
        } catch (Exception $err) {
            throw new Exception($err);
        }
    }
}
