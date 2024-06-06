<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    /**
     * @var string $table
     */
    protected string $table = 'users';

    /**
     * @var array|string[] $hidden
     */
    protected array $hidden = [
        'password'
    ];
}
