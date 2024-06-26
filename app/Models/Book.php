<?php

namespace App\Models;

class Book extends Model
{
    public string $table = 'books';

    // ---------- ATTRIBUTES ----------

    public function available(): Model
    {
        return $this->where(__METHOD__, true);
    }

    public function excerpt(int $max = null): string
    {
        return substr($this->description, 0, $max ?? 100) . '...';
    }
}
