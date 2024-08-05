<?php

namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Helpers\ArrayHelper;
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

    public function create(array $data)
    {
        if (!isset($data['content'])) return;

        $query = "INSERT INTO messages ";
        $query .= "(`conversation_id`, `sender_id`, `receiver_id`, `content`, `created_at`, `updated_at`) ";
        $query .= "VALUES (:conversation_id, :sender_id, :receiver_id, :content, :created_at, :updated_at);";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':conversation_id', $data['conversation_id']);
        $statement->bindValue(':sender_id', $data['sender_id']);
        $statement->bindValue(':receiver_id', $data['receiver_id']);
        $statement->bindValue(':content', $data['content']);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s'));
        $statement->bindValue(':updated_at', date('Y-m-d H:i:s'));

        $result = $statement->execute();

        Log::dd($result);

//        $statement = $this->connection->prepare("SELECT m.* FROM messages m ORDER BY created_at DESC LIMIT 1");
//        $statement->execute();
//        $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getMessages(?int $conversationId = null): array
    {
        $user = Auth::user();
        $messages = [];

        $query = "SELECT " ;
        $query .= "m.id AS message_id,
            m.parent_id AS message_parent_id,
            m.content AS message_content,
            m.sender_id AS message_sender_id,
            m.receiver_id AS message_receiver_id,
            m.created_at AS message_created_at,
            m.updated_at AS message_updated_at, ";

        $query .= "s.id AS sender_id,
            s.username AS sender_username,
            s.avatar AS sender_avatar, ";

        $query .= "r.id AS receiver_id,
            r.username AS receiver_username,
            r.avatar AS receiver_avatar ";

        $query .= "FROM messages AS m ";

        $query .= "INNER JOIN users s ON sender_id = s.id ";
        $query .= "INNER JOIN users r ON receiver_id = r.id ";

        $query .= "WHERE m.sender_id = :sender_id ";
        $query .= "OR m.receiver_id = :receiver_id ";

        if ($conversationId !== null) {
            $query .= "AND m.conversation_id = :conversation_id ";
        }

        $query .= "ORDER BY m.created_at ASC";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':sender_id', $user->id);
        $statement->bindValue(':receiver_id', $user->id);
        if ($conversationId !== null) {
            $statement->bindValue(':conversation_id', $conversationId);
        }
        $statement->execute();
        $messagesRaw = $statement->fetchAll(PDO::FETCH_ASSOC);

        $receiver = null;

        foreach ($messagesRaw as $messageRaw) {

            $message = new Message(ArrayHelper::map(ArrayHelper::normalize($messageRaw, 'message_'), Message::class));

            $message->addRelations('receiver', $this->userManager->getUserById($message->receiver_id));
            $message->addRelations('sender', $this->userManager->getUserById($message->sender_id));

            $messages[] = $message;
        }

        if ($messages[0]->relations['sender']->id !== Auth::user()->id) {
            $receiver = $messages[0]->relations['sender'];
        } else {
            $receiver = $messages[0]->relations['receiver'];
        }

        return [
            $messages,
            $receiver
        ];
    }
}