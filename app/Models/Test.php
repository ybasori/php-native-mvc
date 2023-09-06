<?php

namespace App\Models;

use System\Database as DB;

class Test
{

    public function getAllTest()
    {
        $db = new DB;

        $query = $db->query('SELECT * FROM tests ');
        $query->execute();
        $rows = $query->fetchAll();

        return $rows;
    }
}
