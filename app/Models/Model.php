<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
use PDO;
use PDOStatement;
use stdClass;

abstract class Model
{
    protected PDO $connection;

    protected string $table;

    private array $properties = [];

    private string $query;

    protected PDOStatement $statement;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();

        $this->query = "";
    }

    public function where(string $column, $value): Model
    {
        $query = "SELECT * FROM ";
        $query .= " $this->table WHERE $column = :value;";
        $this->statement = $this->connection->prepare($query);
        $this->statement->bindParam(":value", $value);

        return $this;
    }

    /**
     * Fetch specified keys
     *
     * @param mixed ...$argv
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
    public function first()
    {
        $this->statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        $this->statement->execute();

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