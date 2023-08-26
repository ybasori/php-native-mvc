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


    public function checkExistingSlug($value)
    {

        $expValue = explode("-", $value);

        if (count($expValue) > 1) {
            if (is_numeric($expValue[count($expValue) - 1])) {
                unset($expValue[count($expValue) - 1]);
            }
        }

        $impValue = implode("-", $expValue);
        $query = $this->db->query("SELECT slug, number FROM (SELECT slug,  cast(replace(replace(slug,'$impValue',''),'-','') as int) as number FROM $this->table WHERE slug like '$impValue%') tbl ORDER BY number DESC LIMIT 1");
        $query->execute();

        return $query->fetch();
    }
}
