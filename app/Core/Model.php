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
