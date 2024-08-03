<?php
namespace App\Models;

use App\Core\Auth\Auth;
use PDO;

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

    public function __construct(
        public array $properties = []
    )
    {}
}