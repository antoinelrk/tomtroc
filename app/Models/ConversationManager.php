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

            [$messages, $receiver] = $this->messagesManager->getMessages($conversation->id);

            $conversation->addRelations(
                'messages',
                $messages
            );

            $conversation->addRelations(
                'receiver',
                $receiver
            );

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

    public function createConversation(array $data): Conversation|bool
    {
        $map = (new Conversation())->map;
        $attributes = implode(', ', $map);
        $keys = ':' . implode(', :', $map);

        $sql = "INSERT INTO conversations ($attributes)";
        $sql .= " VALUES ($keys);";
        $statement = $this->connection->prepare($sql);

        foreach ($map as $item) {
            $statement->bindParam(':' . $item, $data[$item]);
        }

        $result = $statement->execute();

        if (!$result) {
            return false;
        }

        return $this->getLastConversation($this->connection->lastInsertId());
    }

    public function getLastConversation(int $id): Conversation
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :authenticated_id";
        $query .= "AND c.id = :conversation_id";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':authenticated_id', Auth::user()->id);
        $statement->bindValue(':conversation_id', $id);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $conversation = new Conversation($result);

        [$messages, $receiver] = $this->messagesManager->getMessages($conversation->id);
        $conversation->addRelations(
            'messages',
            $messages
        );

        $conversation->addRelations(
            'receiver',
            $receiver
        );

        return $conversation;
    }
}