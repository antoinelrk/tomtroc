<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
use PDO;
use PDOStatement;
use Serializable;

abstract class Model implements Serializable
{
    protected string $table;

    public array $properties = [];

    protected array $hidden = [];

    public array $relationships = [];

    public function __construct() {}

    public function setRelationships(string $className, array $data)
    {
        $this->relationships[] = [
            'className' => $data,
        ];
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