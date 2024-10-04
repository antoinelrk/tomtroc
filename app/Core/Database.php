<?php

namespace App\Core;

use App\Helpers\Log;
use PDO;
use PDOException;

class Database
{
    /**
     * @var Database|null
     */
    private static ?Database $instance = null;
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Create new instance of Database
     */
    private function __construct()
    {
        $config = [
            'driver'    => DB_DRIVER,
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USERNAME,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ];

        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * Get database instance.
     *
     * @return Database|null
     */
    public static function getInstance(): ?Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Return database instance of PDO.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Return state of database for debugging.
     *
     * @return void
     */
    public static function debug(): void
    {
        try {
            $db = self::getInstance()->getConnection();
            Log::dd('Connection successfully!');
        } catch (PDOException $e) {
            Log::dd("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * @param string $sql
     * @param string $classname
     * @return false|\PDOStatement
     */
    public static function query(string $sql, string $classname): false|\PDOStatement
    {
        return (new Database())->getConnection()->query($sql);
    }

    /**
     * @param string $sql
     * @return false|\PDOStatement
     */
    public static function prepare(string $sql): false|\PDOStatement
    {
        $statement = (new Database())->getConnection()->prepare($sql);
        $statement->execute();

        return $statement;
    }
}
