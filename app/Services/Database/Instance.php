<?php

namespace App\Services\Database;

use MongoDB\Client as MongoDB;

class Instance
{
    public $db;
    public function __construct()
    {   $db_uri = getenv("DB_URI", null);
        $mongo_db = new MongoDB($db_uri);
        $this->db = $mongo_db->snapShare;
    }
}
