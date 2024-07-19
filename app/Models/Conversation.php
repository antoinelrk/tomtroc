<?php
namespace App\Models;

use App\Core\Auth\Auth;
use PDO;

class Conversation extends Model
{
    public function __construct(
        public array $properties = []
    )
    {}
}