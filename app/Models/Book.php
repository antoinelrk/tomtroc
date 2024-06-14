<?php

namespace App\Models;

use App\Core\Model;
use App\Helpers\Log;
use PDO;

class Book extends Model
{
    public string $table = 'books';

    public function getBooks()
    {
        $query = "SELECT ";
        $query .= "b.*, ";
        $query .= "u.display_name AS publisher_name ";
        $query .= "FROM books b ";
        $query .= "JOIN users u ON b.user_id = u.id ";
        $query .= "WHERE b.available = 1 ";
        $query .= "ORDER BY b.created_at DESC ";
        $query .= ";";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
