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

    private array $pushToBind;

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
    public function withoutHidden(array $data): array
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

    public function whereTest($column, $value): self
    {
        $this->query .= " WHERE {$this->table}.{$column} = :value";
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
        $this->query = "SELECT {$this->table}.{$this->applyOnly()}, {$this->selectable} FROM {$this->table}$this->query;";
        $statement = $this->bindAll();
//        Log::dd($statement);
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

        return implode(", ", $this->only);
    }

    public function getInstance(): self
    {
        return $this;
    }
}
