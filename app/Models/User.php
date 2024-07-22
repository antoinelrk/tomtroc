<?php

namespace App\Models;

class User extends Model
{
    public function __construct(
        public array $properties = []
    ) {}

    public function withoutPassword(): User
    {
        unset($this->properties['password']);
        return $this;
    }

    // ---------- RELATIONS ----------

    public function books(): self
    {
        $books = (new BookManager())->getUserBook($this);
        $this->relations['books'] = $books;

        return $this;
    }
}
