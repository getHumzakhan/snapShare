<?php

namespace App\Services\Database;

use MongoDB\Client as MongoDB;

class Instance
{
    public $db;
    public function __construct()
    {
        $mongo_db = new MongoDB("mongodb+srv://snapShare:HamzaKhanPf@cluster0.mthqt.mongodb.net/snapShare?retryWrites=true&w=majority");
        $this->db = $mongo_db->snapShare;
    }
}
