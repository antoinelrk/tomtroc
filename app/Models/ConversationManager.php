<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
use PDO;

class ConversationManager
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function getConversations()
    {
        /**
         * Récupérer toutes les conversations
         */
        $query = "SELECT * FROM conversations";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);



        return $conversations;
    }
}