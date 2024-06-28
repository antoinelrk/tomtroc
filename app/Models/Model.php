<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOStatement;
use Serializable;

abstract class Model implements Serializable
{
    protected PDO $connection;

    protected string $table;

    public array $properties = [];

    protected array $hidden = [];

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
        if(!isset($this?->statement)) {
            $this->statement = $this->connection->prepare("SELECT * FROM $this->table");
        }

        $this->statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        $this->statement->execute();

        return $this->unsetHiddenAttributesAll($this->statement->fetchAll());
    }

    /**
     * Return first occurence
     */
    public function first(): Model
    {
        if(!isset($this?->statement)) {
            $this->statement = $this->connection->prepare("SELECT * FROM $this->table");
        }

        $this->statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        $this->statement->execute();

        return $this->unsetHiddenAttributes($this->statement->fetch());
    }

    // ---------- OTHER ----------
    public function find(int $id): Model
    {
        $statement = $this->connection->prepare("SELECT * FROM $this->table WHERE id = ?");
        $statement->execute([$id]);

        return $this->unsetHiddenAttributes($statement->fetch());
    }

    public function unsetHiddenAttributesAll(array $models): array
    {
        foreach($models as $model) {
            foreach ($model->hidden as $hiddenColumn) {
                if ($model->properties[$hiddenColumn]) {
                    unset($model->properties[$hiddenColumn]);
                }
            }
        }

        return $models;
    }

    public function unsetHiddenAttributes(Model $model): Model
    {
        foreach ($this->hidden as $hiddenColumn) {
            if ($model->properties[$hiddenColumn]) {
                unset($model->properties[$hiddenColumn]);
            }
        }

        return $model;
    }

    // ---------- GETTER / SETTER ATTRIBUTES ----------
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        return $this->properties[$name];
    }

    public function __sleep(): array
    {
        return array_keys($this->properties);
    }

    public function serialize(): ?string
    {
        return serialize($this->properties);
    }

    public function unserialize($data): void
    {
        $this->properties = unserialize($data);
    }

    public function __serialize(): array
    {
        return $this->properties;
    }

    public function __unserialize(array $data): void
    {
        $this->properties = $data;
    }
}