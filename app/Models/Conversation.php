<?php

namespace App\Models;

use App\Core\Auth\Auth;
use PDO;

/**
 * @property mixed $id
 */
class Conversation extends Model
{
    public array $map = [
        'receiver_id',
        'sender_id',
        'uuid',
        'archived',
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
