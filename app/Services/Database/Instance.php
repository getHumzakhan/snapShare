<?php

namespace App\Services\Database;

use MongoDB\Client as MongoDB;

class Instance
{
    public $db;
    public function __construct()
    {
        $mongo_db = new MongoDB();
        $this->db = $mongo_db->snapShare;
    }
}
