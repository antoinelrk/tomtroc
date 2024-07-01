<?php

namespace App\Core;

use App\Helpers\Log;
use App\Helpers\Str;
use PDO;
use PDOStatement;

class QueryBuilder
{
    protected PDO $connection;

    protected string $tableName;

    protected array $bindings = [];

    protected array $orderBy = [];

    protected array $relations = [];

    protected array $selectors = [];

    protected PDOStatement $statement;

    protected string $queryString = "";

    protected string $baseClass;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function table(string $className): QueryBuilder
    {
        $this->tableName = Str::setDatatable($className);
        $this->baseClass = $className;

        return $this;
    }

    // ---------- RELATIONSHIPS ----------

    public function with(...$argv)
    {
        foreach ($argv as $arg) {
            $this->relations[] = Str::setDatatable($arg);
        }

        return $this;
    }

    // ---------- STATEMENT TYPES ----------

    public function get()
    {
        $this->queryString .= "SELECT * FROM $this->tableName";

        $this->statement = $this->connection->prepare($this->queryString);

        return $this;
    }

    public function create()
    {
        $key = "INSERT INTO {$this->tableName} ";
    }

    public function update()
    {
        $key = "UPDATE {$this->tableName} ";
    }

    public function delete()
    {
        $key = "DELETE FROM {$this->tableName} ";
    }

    // ---------- ENDPOINT ----------

    public function all()
    {
        /**
         * Ici on doit merge toutes les parties de la query
         */
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->baseClass);
        $this->statement->execute();
        $result = $this->statement->fetchAll();
        return $result;
    }

    public function first()
    {
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->baseClass);
        return $this->statement->fetch();
    }
}