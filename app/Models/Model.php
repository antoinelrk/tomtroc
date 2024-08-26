<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
use PDO;
use PDOStatement;
use Serializable;

abstract class Model implements Serializable
{
    public array $map = [
        'id'
    ];

    public array $properties = [];

    public array $relations = [];

    // ---------- GETTER / SETTER ATTRIBUTES ----------
    public function addRelations(string $model, mixed $relation): void
    {
        $this->relations[$model] = $relation;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

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