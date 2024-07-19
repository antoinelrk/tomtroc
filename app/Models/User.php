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
}
