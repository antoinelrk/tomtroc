<?php

namespace App\Models;

/**
 * @property mixed $id
 * @property mixed $avatar
 */
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
