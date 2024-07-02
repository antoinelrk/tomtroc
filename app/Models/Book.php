<?php

namespace App\Models;

class Book
{
    protected array $relations = [];

    public function __construct(
        public ?int $id = null,
        public ?string $title = null,
        public ?string $author = null,
        public ?string $description = null,
        public ?string $cover = null,
        public ?bool $available = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    )
    {

    }

    public function __set($name, $value)
    {
        return $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function addRelations(array $relation)
    {
        $this->relations[] = $relation;
    }

    public function getRelations()
    {
        return $this->relations;
    }
}
