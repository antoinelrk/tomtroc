<?php

namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Helpers\Log;
use PDO;
use PDOException;

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

    public function create(array $data): Conversation|bool
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

        $statement->execute();
        $conversationId = $this->connection->lastInsertId();

        $this->attachUsersToConversation(
            $conversationId,
            $data['sender_id'],
            $data['receiver_id']
        );

        // On créé le message
        $this->messagesManager->create([
            'conversation_id' => $conversationId,
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'content' => $data['content'],
        ]);


        return $this->getLastConversation($conversationId);
    }

    protected function attachUsersToConversation(int $conversationId, int $senderId, int $receiverId): void
    {
        $sql = "INSERT INTO conversation_user (conversation_id, user_id) VALUES (:conversation_id, :user_id)";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':user_id', $senderId);
        $statement->bindValue(':conversation_id', $conversationId);

        $statement->execute();

        $sql = "INSERT INTO conversation_user (conversation_id, user_id) VALUES (:conversation_id, :user_id)";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':user_id', $receiverId);
        $statement->bindValue(':conversation_id', $conversationId);

        $statement->execute();
    }

    public function getLastConversation(int $id): Conversation
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :authenticated_id";
        $query .= "AND c.id = :conversation_id";
        $query .= "ORDER BY c.id DESC LIMIT 1";

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