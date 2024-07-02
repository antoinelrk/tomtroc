<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
use PDO;

class BookManager
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function getBooks()
    {
        /**
         * Faire les queries ici et tout passer en Objet (Models), osef des performances
         */
        $query = "SELECT * FROM books";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $booksRaw = $statement->fetchAll(PDO::FETCH_OBJ);
        $books = [];

        $userManager = new UserManager();

        foreach ($booksRaw as $bookRaw) {
            $book = new Book(
                $bookRaw->id,
                $bookRaw->title,
                $bookRaw->author,
                $bookRaw->description,
                $bookRaw->cover,
                $bookRaw->available,
                $bookRaw->created_at,
                $bookRaw->updated_at,
            );

            $user = $userManager->getUserById($bookRaw->user_id);

            $book->addRelations(['user' => $user]);

            $books[] = $book;
        }

        Log::dd($books);

        return $books;
    }
}