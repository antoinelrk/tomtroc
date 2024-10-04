<?php

namespace App\Services;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Core\Notification;
use App\Enum\EnumFileCategory;
use App\Enum\EnumNotificationState;
use App\Helpers\Diamond;
use App\Helpers\File;
use App\Helpers\Log;
use App\Helpers\Str;
use App\Models\Book;
use App\Models\User;
use PDO;
use PDOException;

class BookService extends Service
{
    public function __construct(
        protected UserService $userService = new UserService(),
    )
    {
        parent::__construct();
    }

    public function getLastBooks($number = 4): array
    {
        return array_slice($this->getBooks(true), 0, $number);
    }

    public function getBooks(?bool $available = null): array
    {
        if ($available)
        {
            $query = "SELECT * FROM books WHERE available = :available ORDER BY created_at DESC;";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
        }
        else
        {
            $query = "SELECT * FROM books";
            $statement = $this->connection->prepare($query);
        }

        $statement->execute();

        $booksRaw = $statement->fetchAll(PDO::FETCH_ASSOC);
        $books = [];

        foreach ($booksRaw as $bookRaw)
        {
            $book = new Book($bookRaw);

            $user = $this->userService->getUserById($bookRaw['user_id']);

            $book->addRelations('user', $user);

            $books[] = $book;
        }

        return $books;
    }

    public function getUserBook(User $user, ?bool $available = null): array
    {
        $books = [];

        if ($available)
        {
            $query = "SELECT * FROM books WHERE available = :available AND user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
            $statement->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        }
        else
        {
            $query = "SELECT * FROM books WHERE user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        }

        $statement->execute();
        $booksRaw = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($booksRaw as $bookRaw)
        {
            $book = new Book($bookRaw);
            $books[] = $book;
        }

        return $books;
    }

    public function getLastBook(int $id): ?Book
    {
        $query = "SELECT * FROM books WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $bookRaw = $statement->fetch(PDO::FETCH_ASSOC);

        $book = new Book($bookRaw);
        $user = $this->userService->getUserById($bookRaw['user_id']);
        $book->addRelations('user', $user);

        return $book;
    }

    public function getBook(string $slug, ?bool $available = null): Book
    {
        if ($available)
        {
            $query = "SELECT * FROM books WHERE slug = :slug AND available = :available";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':slug', $slug);
            $statement->bindValue(':available', $available, PDO::PARAM_BOOL);
        }
        else
        {
            $query = "SELECT * FROM books WHERE slug = :slug";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':slug', $slug);
        }

        $statement->execute();
        $bookRaw = $statement->fetch(PDO::FETCH_ASSOC);

        $book = new Book($bookRaw);
        $user = $this->userService->getUserById($bookRaw['user_id']);
        $book->addRelations('user', $user);

        return $book;
    }

    public function create(array $data): Book|bool
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

        foreach ($map as $item)
        {
            $statement->bindParam(':' . $item, $data[$item]);
        }

        $result = $statement->execute();

        if (!$result)
        {
            return false;
        }

        return $this->getLastBook($this->connection->lastInsertId());
    }

    public function update(Book $book, array $data): Book|bool
    {
        $this->connection->beginTransaction();

        $data = $this->prepareData($data);

        $sql = "UPDATE books SET ";
        $setParts = array_map(fn($key) => "$key = :$key", array_keys($data));

        if (isset($data['cover']))
        {
            $filename = $this->setCover($book, $data['cover']);

            if (!$filename) {
                $this->connection->rollBack();

                return false;
            }

            $data['cover'] = $filename;
        }

        $sql .= implode(', ', $setParts);
        $sql .= " WHERE id = :id;";

        $statement = $this->connection->prepare($sql);

        foreach ($data as $key => $value)
        {
            $statement->bindValue(":$key", $value);
        }

        $statement->bindValue(":id", $book->id);

        if (!$statement->execute()) {
            $this->connection->rollBack();

            return false;
        }

        $this->connection->commit();

        return $this->getLastBook($book->id);
    }

    /**
     * @param Book $book
     * @param array $cover
     * @return bool|string
     * @throws \Random\RandomException
     */
    private function setCover(Book $book, array $cover): bool|string
    {
        if ($cover['error'] !== UPLOAD_ERR_OK)
        {
            Notification::push('L\'image n\'est pas valide', EnumNotificationState::ERROR->value);
            return false;
        }

        $mime = mime_content_type($cover['tmp_name']);
        $authorizedMimes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
        ];

        if ($mime && !in_array($mime, $authorizedMimes)) {
            Notification::push(
                'L\'image doit être au format: jpeg, jpg ou png',
                EnumNotificationState::ERROR->value
            );

            return false;
        }

        if ($cover['size'] > 5000000)
        {
            Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);
            return false;
        }

        if ($book->cover !== null)
        {
            File::delete($book->cover, EnumFileCategory::BOOK->value);
        }

        if ($cover['type'] === 'image/jpeg' || $cover['type'] === 'image/png')
        {
            if (($filename = File::store(EnumFileCategory::BOOK->value, $cover)))
            {
                return $filename;
            }

            return false;
        }

        return false;
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function delete(Book $book): bool
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

    /**
     * @param array $data
     * @return array
     */
    private function prepareData(array $data): array
    {
        $slug = Str::slug($data['title']);

        return [
            ...$data,
            'slug' => $slug,
        ];
    }
}