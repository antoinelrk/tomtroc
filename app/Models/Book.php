<?php

namespace App\Models;

class Book extends Model
{
    public array $map = [
        'id',
        'title',
        'slug',
        'author',
        'cover',
        'description',
        'available',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function __construct(
        public array $properties = []
    ) {}
}
