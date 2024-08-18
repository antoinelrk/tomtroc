<?php

namespace App\Services;

use App\Core\Auth\Auth;
use App\Core\Notification;
use App\Enum\EnumFileCategory;
use App\Enum\EnumNotificationState;
use App\Helpers\File;
use App\Helpers\Diamond;
use App\Helpers\Hash;
use App\Helpers\Log;
use App\Models\User;
use PDO;
use Random\RandomException;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    // TODO: A REFACTOR
    public function getUserByName(string $username): ?User
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":username", $username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if(is_bool($result)) {
            Notification::push(
                'L\'utilisateur ' . $username . ' n\'existe pas',
                EnumNotificationState::ERROR->value
            );

            return null;
        } else {
            $user = new User($result);

            return $user->withoutPassword();
        }
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result !== false) {
            $user = new User($result);

            return $user->withoutPassword();
        }

        return null;
    }
    // TODO END
    public function create(array $data): User
    {
        $query = "INSERT INTO users ";
        $query .= "(username, password, email, created_at, updated_at) VALUES ";
        $query .= "(:username, :password, :email, :created_at, :updated_at)";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':username', $data['username']);
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

    public function update(User $user, array $data): bool
    {
        $sql = "UPDATE users SET ";

        $setParts = array_map(fn($key) => "$key = :$key", array_keys($data));

        if (isset($data['avatar'])) {
            $filename = $this->setAvatar($user, $data['avatar']);

            if (!is_bool($filename)) {
                $setParts[] = "avatar = :avatar";
                $data['avatar'] = $filename;
            }
        }

        $sql .= implode(', ', $setParts);

        $sql .= " WHERE id = :id;";

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
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws RandomException
     */
    public function setAvatar(User $user, array $avatar): bool|string
    {
        if ($avatar['error'] !== UPLOAD_ERR_OK) {
            Notification::push('L\'image n\'est pas valide', EnumNotificationState::ERROR->value);
            return false;
        }

        if ($avatar['size'] > 5000000) {
            Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);
            return false;
        }

        if ($user->avatar !== null) {
            File::delete($user->avatar, EnumFileCategory::AVATAR->value);
        }

        if ($avatar['type'] === 'image/jpeg' || $avatar['type'] === 'image/png') {
            if (($filename = File::store('avatars', $avatar))) {
                return $filename;
            }

            return false;
        }

        return false;
    }
}