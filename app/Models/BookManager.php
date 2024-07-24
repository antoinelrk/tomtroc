<?php

namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Core\Notification;
use App\Core\Response;
use App\Enum\EnumFileCategory;
use App\Helpers\Diamond;
use App\Helpers\File;
use App\Helpers\Str;
use PDO;
use PDOException;

class BookManager
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function getBooks(?bool $available = null): array
    {
        if ($available) {
            $query = "SELECT * FROM books WHERE available = :available;";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
            $statement->execute();
        } else {
            $query = "SELECT * FROM books";
            $statement = $this->connection->prepare($query);
            $statement->execute();
        }

        $booksRaw = $statement->fetchAll(PDO::FETCH_ASSOC);
        $books = [];

        $userManager = new UserManager();

        foreach ($booksRaw as $bookRaw) {
            $book = new Book($bookRaw);

            $user = $userManager->getUserById($bookRaw['user_id']);

            $book->addRelations('user', $user);

            $books[] = $book;
        }

        return $books;
    }

    public function getUserBook(User $user, ?bool $available = null)
    {
        $books = [];

        if ($available) {
            $query = "SELECT * FROM books WHERE available = :available AND user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
            $statement->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM books WHERE user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        }

        $statement->execute();
        $booksRaw = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($booksRaw as $bookRaw) {
            $book = new Book($bookRaw);
            $books[] = $book;
        }

        return $books;
    }

    public function getBook(string $slug, ?bool $available = null): Book
    {
        if ($available) {
            $query = "SELECT * FROM books WHERE slug = :slug AND available = :available";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':slug', $slug);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
        } else {
            $query = "SELECT * FROM books WHERE slug = :slug";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':slug', $slug);
        }

        $statement->execute();
        $bookRaw = $statement->fetch(PDO::FETCH_ASSOC);

        $book = new Book($bookRaw);
        $user = (new UserManager())->getUserById($bookRaw['user_id']);
        $book->addRelations('user', $user);

        // TODO: Le livre n'existe peut-etre pas !

        return $book;
    }

    public function create(array $data)
    {
        $data = [
            ...$this->prepareData($data),
            'user_id' => Auth::user()->id,
            'created_at' => Diamond::now(),
            'updated_at' => Diamond::now()
        ];

        $map = (new Book())->map;
        $attributes = implode(', ', $map);
        $keys = ':' . implode(', :', $map);

        $sql = "INSERT INTO books ($attributes)";
        $sql .= " VALUES ($keys);";
        $statement = $this->connection->prepare($sql);

        foreach ($map as $item) {
            $statement->bindParam(':' . $item, $data[$item]);
        }

        // TODO: A vérifier
        return $statement->execute() ? new Book($this->connection->lastInsertId()) : false;
    }

    public function update(Book $book, array $data)
    {
        $data = [
            ...$this->prepareData($data),
            'updated_at' => Diamond::now(),
        ];

        $line = "";

        foreach ($data as $key => $value) {
            if ($key === array_key_last($data)) {
                $line .= $key . " = :" . $key;
            } else {
                $line .= $key . " = :" . $key . ", ";
            }
        }

        $sql = "UPDATE books SET $line WHERE id = :id;";
        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':id', $book->id);

        foreach (array_keys($data) as $item) {
            if ($item === 'available') {
                $statement->bindParam(':' . $item, $data[$item], PDO::PARAM_INT);
            } else {
                $statement->bindParam(':' . $item, $data[$item]);
            }
        }

        if ($statement->execute()) {
            Notification::push('Le livre ' . $book->title . ' a bien été modifié', 'success');
            Response::redirect('/books/show/' . $book->slug);
        } else {
            Notification::push('Impossible de modifier: ' . $book->title . ', contactez un administrateur', 'error');
            Response::redirect('/books/edit/' . $book->slug);
        }
    }

    public function delete(Book $book)
    {
        $this->connection->beginTransaction();

        try {
            $sql = "DELETE FROM books WHERE id = :id;";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(':id', $book->id);
            $statement->execute();

            $this->connection->commit();

            // TODO: Penser à changer toutes les variables statiques
            File::delete($book->cover, EnumFileCategory::BOOK->value);

            return true;
        } catch (PDOException $e) {
            $this->connection->rollBack();
        }

        return false;
    }

    private function prepareData(array $data): array
    {
        $slug = Str::slug($data['title']);

        return [
            ...$data,
            'slug' => $slug,
        ];
    }
}