<?php

namespace App\Models;

use System\Model;

class Author extends Model
{

    public $table = "authors";

    function __construct()
    {
        parent::__construct();
    }
}
