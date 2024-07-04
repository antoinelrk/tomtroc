<?php

namespace App\Models;

class Message extends Model
{
    public function __construct(
        public array $properties = []
    ) {}
}