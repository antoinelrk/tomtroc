<?php

namespace App\Models;

use App\Core\Database;
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
        $result = $statement->fetch(PDO::FETCH_OBJ);

        $user = new User(
            $result->id,
            $result->username,
            $result->display_name,
            $result->email,
            $result->password,
            $result->avatar,
            $result->created_at,
            $result->updated_at,
        );

        return $user;
    }
}