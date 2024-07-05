<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Log;
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
        $query .= "ORDER BY m.created_at ASC";

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

    public function create(array $data)
    {
        if (!isset($data['content'])) return;

        $query = "INSERT INTO messages ";
        $query .= "(`conversation_id`, `user_id`, `receiver_id`, `content`, `created_at`, `updated_at`) ";
        $query .= "VALUES (:conversation_id, :user_id, :receiver_id, :content, :created_at, :updated_at);";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':conversation_id', $data['conversation_id']);
        $statement->bindValue(':user_id', $data['user_id']);
        $statement->bindValue(':receiver_id', $data['receiver_id']);
        $statement->bindValue(':content', $data['content']);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s'));
        $statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
        $statement->execute();

//        $statement = $this->connection->prepare("SELECT m.* FROM messages m ORDER BY created_at DESC LIMIT 1");
//        $statement->execute();
//        $statement->fetch(PDO::FETCH_ASSOC);
    }
}