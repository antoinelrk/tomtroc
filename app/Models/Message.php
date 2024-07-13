<?php

namespace App\Models;

class Message extends Model
{
    public array $map = [
        'id',
        'parent_id',
        'content',
        'sender_id',
        'receiver_id',
        'created_at',
        'updated_at',
    ];

    public function __construct(
        public array $properties = []
    ) {}
}