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

    protected UserManager $usersManager;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
        $this->messagesManager = new MessagesManager();
        $this->usersManager = new UserManager();
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

        return $result;
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

            $messages = $this->messagesManager->getAllMessages();

            $conversation->addRelations('messages', $messages);

            $conversations[] = $conversation;
        }

        return $conversations;
    }

    public function getFirstConversation()
    {
        $conversation = $this->getConversations();

        if (!empty($conversation)) {
            return $conversation[0];
        }

        return [];
    }

    public function getConversationByUserId(int $userId)
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :user_id;";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}