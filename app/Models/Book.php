<?php

namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Model;
use App\Helpers\Log;
use PDO;

class Book extends Model
{
    public string $table = 'books';

    public function __construct()
    {
        parent::__construct();
    }

    public function users(...$argv): Model
    {
        // TODO: Si argv est null, on ajoute étoile à la query.
        $model = 'users';
        $mapped = array_map(function($element) use ($model) {
            return $model . '.' . $element;
        }, $argv);

        $this->getInstance()->selectable .= ", " . implode(', ', $mapped);

        $this->getInstance()->query .= " INNER JOIN {$model} ON {$model}.id = {$this->table}.user_id";
        return $this->getInstance();
    }

    public function myBooks(): ?array
    {
        $query = "SELECT * ";
        $query .= "FROM books b ";
        $query .= "WHERE b.user_id = :user_id";
        $query .= ";";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':user_id', Auth::user()['id']);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

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
