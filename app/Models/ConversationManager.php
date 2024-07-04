<?php

namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Helpers\Log;
use PDO;

class ConversationManager
{
    protected PDO $connection;

    protected MessagesManager $messagesManager;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
        $this->messagesManager = new MessagesManager();
    }

    public function getConversationByUuid(string $uuid)
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :authenticated_id";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':authenticated_id', Auth::user()->id);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        Log::dd($result);
    }

    public function getConversations()
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :authenticated_id";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':authenticated_id', Auth::user()->id);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $conversations = [];

        foreach ($results as $result) {
            $conversation = new Conversation($result);
            $conversation->addRelations([
                'messages' => $this->messagesManager->getMessages($conversation->id),
            ]);

            $conversations[] = $conversation;
        }

        return $conversations;
    }
}