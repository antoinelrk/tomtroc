<?php

namespace App\Services;

use App\Core\Database;

abstract class Service
{
    protected \PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }
}