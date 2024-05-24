<?php

namespace App\Core;

class Model
{
    protected $table;
    protected string $primaryKey = 'id';
    protected \PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()
            ->getConnection();
    }

    public function all(): false|array
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function create(array $data) {
        $keys = implode(',', array_keys($data));
        $values = ':' . implode(',:', array_keys($data));
        $stmt = $this->connection->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $this->find($this->connection->lastInsertId());
    }

    public function update($id, array $data) {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }

        $set = implode(',', $set);
        $stmt = $this->connection->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $this->find($id);
    }

    public function delete($id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }
}
