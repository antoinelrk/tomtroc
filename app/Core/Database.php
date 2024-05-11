<?php

namespace App\Core;

use PDO;
use PDOStatement;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Create new instance of database.
     * TODO: Setup .env file.
     *
     * @return PDO
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=localhost;dbname=test;charset=utf8';
            $username = 'username';
            $password = 'password';
            self::$instance = new PDO($dsn, $username, $password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
