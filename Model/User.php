<?php

namespace Model;

use Model\General\Database;

class User extends Database
{
    public function create($array)
    {
        // return $this->select("SELECT * FROM users ORDER BY user_id ASC LIMIT ?", ["i", $limit]);
    }
}
