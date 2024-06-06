<?php

namespace App\Core;

use PDO;

class Model
{
    /**
     * @var string $table
     */
    protected string $table;

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

    /**
     * @var PDO
     */
    protected PDO $connection;

    /**
     * Object constructor.
     */
    public function __construct()
    {
        $this->connection = Database::getInstance()
            ->getConnection();
    }

    /**
     * Exclude hidden's columns.
     * @param array $data
     *
     * @return array
     */
    protected function withoutHidden(array $data): array
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
}
