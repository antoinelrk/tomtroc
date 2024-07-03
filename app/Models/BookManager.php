<?php

namespace App\Models;

use App\Core\Auth\Auth;
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
        $query = "SELECT * FROM books";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $booksRaw = $statement->fetchAll(PDO::FETCH_ASSOC);
        $books = [];

        $userManager = new UserManager();

        foreach ($booksRaw as $bookRaw) {
            $book = new Book($bookRaw);

            $user = $userManager->getUserById($bookRaw['user_id']);

            $book->addRelations(['user' => $user]);

            $books[] = $book;
        }

        return $books;
    }

    public function getUserBook(bool $available = true)
    {
        $books = [];

        if ($available) {
            $query = "SELECT * FROM books WHERE available = :available AND user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
            $statement->bindValue(':user_id', Auth::user()->id, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM books WHERE user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':user_id', Auth::user()->id, PDO::PARAM_INT);
        }

        $statement->execute();
        $booksRaw = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($booksRaw as $bookRaw) {
            $book = new Book($bookRaw);
            $books[] = $book;
        }

        return $books;
    }
}