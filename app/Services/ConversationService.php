<?php

namespace App\Services;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Helpers\Log;
use App\Models\Conversation;
use PDO;

class ConversationService
{
    protected PDO $connection;

    protected MessagesService $messagesManager;

    protected UserService $usersManager;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
        $this->messagesManager = new MessagesService();
        $this->usersManager = new UserService();
    }

    public function getConversationByUuid(string $uuid): array
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :authenticated_id";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':authenticated_id', Auth::user()->id);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getConversations(): array
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :authenticated_id ";
        $query .= "ORDER BY c.updated_at DESC";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':authenticated_id', Auth::user()->id);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $conversations = [];

        foreach ($results as $result)
        {
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

    public function getFirstConversation(): ?Conversation
    {
        $conversation = $this->getConversations();

        if (!empty($conversation))
        {
            return $conversation[0];
        }

        return null;
    }

    public function getConversationByUserId(int $userId): array
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE cu.user_id = :user_id;";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): Conversation|bool
    {
        $map = (new Conversation())->map;
        $attributes = implode(', ', $map);
        $keys = ':' . implode(', :', $map);

        $sql = "INSERT INTO conversations ($attributes)";
        $sql .= " VALUES ($keys);";
        $statement = $this->connection->prepare($sql);

        foreach ($map as $item)
        {
            $statement->bindParam(':' . $item, $data[$item]);
        }

        $statement->execute();
        $conversationId = $this->connection->lastInsertId();

        $this->attachUsersToConversation(
            $conversationId,
            $data['sender_id'],
            $data['receiver_id']
        );

        return $this->getLastConversation($conversationId);
    }

    protected function attachUsersToConversation(int $conversationId, int $senderId, int $receiverId): void
    {
        $sql = "INSERT INTO conversation_user (conversation_id, user_id) VALUES (:conversation_id, :user_id)";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':user_id', $senderId);
        $statement->bindValue(':conversation_id', $conversationId);

        $statement->execute();

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':user_id', $receiverId);
        $statement->bindValue(':conversation_id', $conversationId);

        $statement->execute();
    }

    public function getLastConversation($id): Conversation
    {
        $query = "SELECT c.* FROM conversations c ";
        $query .= "INNER JOIN conversation_user cu ON c.id = cu.conversation_id ";
        $query .= "WHERE c.id = :conversation_id ";
        $query .= "ORDER BY c.id DESC LIMIT 1";

        $statement = $this->connection->prepare($query);
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

    public function refresh(int $conversationId): void
    {
        $query = "UPDATE conversations SET ";
        $query .= "updated_at = :updated_at ";
        $query .= "WHERE id = :conversation_id ";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
        $statement->bindValue(':conversation_id', $conversationId);
        $statement->execute();
    }
}