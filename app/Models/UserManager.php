<?php

namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Core\File;
use App\Core\Notification;
use App\Core\Response;
use App\Helpers\Diamond;
use App\Helpers\Hash;
use App\Helpers\Log;
use PDO;

class UserManager
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $user = new User($result);

        return $user->withoutPassword();
    }

    public function create(array $data)
    {
        $query = "INSERT INTO users ";
        $query .= "(username, display_name, password, email, created_at, updated_at) VALUES ";
        $query .= "(:username, :display_name, :password, :email, :created_at, :updated_at)";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':username', $data['username']);
        $statement->bindValue(':display_name', $data['display_name']);
        $statement->bindValue(':password', $data['password']);
        $statement->bindValue(':email', $data['email']);
        $statement->bindValue(':created_at', Diamond::now());
        $statement->bindValue(':updated_at', Diamond::now());
        $statement->execute();

        $lastId = $this->connection->lastInsertId();

        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $lastId]);
        $user = new User($stmt->fetch(PDO::FETCH_ASSOC));

        return $user;
    }

    public function update(User $user, array $data)
    {
        $sql = "UPDATE users SET ";
        $setParts = array_map(fn($key) => "$key = :$key", array_keys($data));
        $sql .= implode(', ', $setParts);

        $sql .= " WHERE id = :id";
        $sql .= ";";

        $statement = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            if ($key === "password") {
                $statement->bindValue(":$key", Hash::make($value));
            }
            else {
                $statement->bindValue(":$key", $value);
            }

        }

        $statement->bindValue(":id", $user->id);

        $state = $statement->execute();

        if ($state) {
            $email = $data['email'] ?? null;
            Auth::refresh($email);
        } else {
            Notification::push('Impossible de mettre à jour le profil, contactez un administrateur.', 'error');
            Response::redirect('/me');
        }
    }

    public function setAvatar(User $user, array $data)
    {
        $connection = Database::getInstance()->getConnection();
        $connection->beginTransaction();

        try {
            if (($path = File::store('./storage/avatars/', $data)) === false) {
                Notification::push('Impossible d\'enregistrer le fichier, contactez un administrateur!', 'error');
                Response::redirect('/me');
            }

            $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(':avatar', $path);
            $statement->bindValue(':id', $user->id);
            $state = $statement->execute();

            if (!$state) {
                Notification::push('Impossible d\'enregistrer le fichier, contactez un administrateur!', 'error');
                Response::redirect('/me');
            }

            $connection->commit();

            Auth::refresh();
        } catch (\PDOException $e) {
            $connection->rollBack();
        }
    }
}