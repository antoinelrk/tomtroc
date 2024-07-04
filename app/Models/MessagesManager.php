<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class MessagesManager
{
    protected PDO $connection;

    protected UserManager $userManager;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
        $this->userManager = new UserManager();
    }

    public function getMessages(int $conversationId)
    {
        $messages = [];

        $query = "SELECT m.* FROM messages m ";
        $query .= "WHERE m.conversation_id = :conversation_id ";
        $query .= "ORDER BY m.updated_at DESC";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':conversation_id', $conversationId);
        $statement->execute();
        $messagesRaw = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($messagesRaw as $messageRaw) {
            $message = new Message($messageRaw);

            $message->addRelations([
                'user' => $this->userManager->getUserById($message->user_id),
            ]);

            $messages[] = $message;
        }

        return $messages;
    }
}