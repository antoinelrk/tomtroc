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
        'readed',
        'created_at',
        'updated_at',
    ];

    /**
     * @param array $properties
     */
    public function __construct(
        public array $properties = []
    ) {
    }
}
