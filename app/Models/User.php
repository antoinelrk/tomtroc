<?php

namespace App\Models;

class User
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $username = null,
        protected ?string $display_name = null,
        protected ?string $email = null,
        protected ?string $password = null,
        protected ?string $avatar = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
    )
    {
    }
}
