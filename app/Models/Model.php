<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
use PDO;
use stdClass;

abstract class Model
{
    protected PDO $connection;

    protected string $table;

    private array $properties = [];

    protected static $statement;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function where(string $column, $value): Model
    {
        $this->statement = $this->connection->prepare("SELECT $keys FROM $this->table");
        
        return $this;
    }

    /**
     * Fetch specified keys
     * 
     * @return self
     */
    public function only(...$argv): Model
    {
        $keys = implode(', ', $argv);
        $this->statement = $this->connection->prepare("SELECT $keys FROM $this->table");
        $this->statement->execute();
        
        return $this;
    }

    /**
     * Return all data
     */
    public function all(): array
    {
        $this->statement = $this->connection->query("SELECT * FROM $this->table");
        $this->statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));

        return $this->statement->fetchAll();
    }
    
    /**
     * Return data
     */
    public function get(): array
    {
        $this->statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));

        return $this->statement->fetchAll();
    }

    /**
     * Return first occurence
     */
    public function first(): Model
    {
        $this->statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));

        return $this->statement->fetch();
    }

    // ---------- OTHER ----------
    public function find(int $id): Model
    {
        $statement = $this->connection->prepare("SELECT * FROM $this->table WHERE id = ?");
        $statement->execute([$id]);

        return $statement->fetch();
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        return $this->properties[$name];
    }
}