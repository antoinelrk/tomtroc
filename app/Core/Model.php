<?php

namespace App\Core;

use App\Helpers\Log;
use PDO;
use PDOStatement;

class Model
{
    /**
     * @var string $table
     */
    protected string $table;

    private array $pushToBind = [];

    /**
     * Define default identification key.
     *
     * @var string $primaryKey
     */
    protected string $primaryKey = 'id';

    /**
     * Extract column to fetch (example: password)
     *
     * @var array $hidden
     */
    protected array $hidden = [];

    protected array $only = [];

    /**
     * @var PDO
     */
    protected PDO $connection;

    protected string $query;

    public string $selectable;

    /**
     * Object constructor.
     */
    public function __construct()
    {
        $this->connection = Database::getInstance()
            ->getConnection();

        $this->query = "";
        $this->selectable = "";
    }

    /**
     * Exclude hidden's columns.
     * @param array $data
     *
     * @return array
     */
    public function withoutHidden(mixed $data): array
    {
        foreach ($this->hidden as $hiddenColumn) {
            if (isset($data[$hiddenColumn])) {
                unset($data[$hiddenColumn]);
            }
        }

        return $data;
    }

    /**
     * Fetch all data on specific table
     *
     * @return false|array
     */
    public function all(): false|array
    {
        $statement = $this->connection->prepare("SELECT * FROM {$this->table}");
        $statement->execute();
        $data = $statement->fetchAll();

        return array_map(
            [$this, 'withoutHidden'], $data
        );
    }

    /**
     * Fetch specific data on specific table
     *
     * @param $id
     *
     * @return false|array
     */
    public function find($id): false|array
    {
        $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();

        return $this->withoutHidden($statement->fetch());
    }

    /**
     * Fetch data with where :column condition.
     *
     * @param $column
     * @param $operator
     * @param $value
     *
     * @return array|false
     */
    public function where($column, $operator, $value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':value', $value);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function orderBy($column, $direction)
    {
        $this->query .= " ORDER BY {$column} {$direction} ";

        return $this;
    }

    public function whereTest($column, $value, $table = null): self
    {
        $table = $table ?? $this->table;
        $this->query .= " WHERE {$table}.{$column} = :value";
        $this->pushToBind[] = [ 'value' => $value ];

        return $this;
    }

    public function only(): self
    {
        $this->only = array_map(function($arg) {
            return $this->table . "." . $arg;
        }, func_get_args());

        return $this;
    }

    public function get(): false|array
    {
        $this->query = "SELECT {$this->table}.{$this->applyOnly()}{$this->selectable} FROM {$this->table}$this->query;";
        $statement = $this->bindAll();
        // Log::dd($statement);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new model type entry in database
     *
     * @param array $data
     *
     * @return false|array
     */
    public function create(array $data): false|array
    {
        $keys = implode(',', array_keys($data));
        $values = ':' . implode(',:', array_keys($data));
        $statement = $this->connection->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $this->find($this->connection->lastInsertId());
    }

    public function first()
    {
        $this->query = "SELECT {$this->table}.{$this->applyOnly()}{$this->selectable} FROM {$this->table}$this->query;";
        $statement = $this->bindAll();
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (is_array($data)) {
            return $this->withoutHidden($data);
        }

        return false;
    }

    /**
     * Update entry in database.
     * TODO: Create validation request
     *
     * @param $id
     * @param array $data
     *
     * @return false|array
     */
    public function update($id, array $data): false|array
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }

        $set = implode(',', $set);
        $statement = $this->connection->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = :id");
        $statement->bindParam(':id', $id);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $this->find($id);
    }

    /**
     * Delete specific entry.
     *
     * @param $id
     *
     * @return void
     */
    public function delete($id): void
    {
        $statement = $this->connection->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $statement->bindParam(':id', $id);

        $statement->execute();
    }

    private function bindAll(): false|PDOStatement
    {
        $statement = $this->connection->prepare($this->query);

        foreach ($this->pushToBind as $value) {
            $statement->bindValue(':value', $value['value']);
        }

        return $statement;
    }

    private function applyOnly(): string
    {
        if (count($this->only) === 0)
        {
            return "*";
        }

        return ", " . implode(", ", $this->only);
    }

    public function getInstance(): self
    {
        return $this;
    }
}
