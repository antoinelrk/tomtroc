<?php

namespace App\Services;

use App\Core\Auth\Auth;
use App\Core\Database;
use App\Helpers\ArrayHelper;
use App\Helpers\Log;
use App\Models\Message;
use PDO;

class MessagesService
{
    protected PDO $connection;

    public function __construct(protected $userService = new UserService())
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    /**
     * @param array $data
     * @return Message|bool
     */
    public function create(array $data): Message|bool
    {
        if (!isset($data['content'])) return false;

        $query = "INSERT INTO messages ";
        $query .= "(`conversation_id`, `sender_id`, `receiver_id`, `content`, `readed`, `created_at`, `updated_at`) ";
        $query .= "VALUES (:conversation_id, :sender_id, :receiver_id, :content, :readed, :created_at, :updated_at);";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':conversation_id', $data['conversation_id']);
        $statement->bindValue(':sender_id', $data['sender_id']);
        $statement->bindValue(':receiver_id', $data['receiver_id']);
        $statement->bindValue(':content', $data['content']);
        $statement->bindValue(':readed', 0);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s'));
        $statement->bindValue(':updated_at', date('Y-m-d H:i:s'));

        $statement->execute();

        return true;
    }

    /**
     * @param int|null $conversationId
     * @return array
     */
    public function getMessages(?int $conversationId = null): array
    {
        $messages = [];

        $query = "SELECT ";
        $query .= "m.id AS message_id,
            m.parent_id AS message_parent_id,
            m.content AS message_content,
            m.sender_id AS message_sender_id,
            m.receiver_id AS message_receiver_id,
            m.readed AS message_readed,
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

        $query .= "WHERE m.conversation_id = :conversation_id ";

        if ($conversationId !== null) {
            $query .= "AND m.conversation_id = :conversation_id ";
        }

        $query .= "ORDER BY m.created_at ASC";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':conversation_id', $conversationId);

        $statement->execute();
        $messagesRaw = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($messagesRaw as $messageRaw) {
            $message = new Message(ArrayHelper::map(ArrayHelper::normalize($messageRaw, 'message_'), Message::class));

            $message->addRelations('receiver', $this->userService->getUserById($message->receiver_id));
            $message->addRelations('sender', $this->userService->getUserById($message->sender_id));

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

    /**
     * @param int|null $userId
     * @return int
     */
    public function countUnreadMessages(int $userId = null): int
    {
        $query = "SELECT COUNT(*) AS unread_messages ";
        $query .= "FROM messages ";
        $query .= "WHERE receiver_id = :receiver_id ";
        $query .= "AND readed = :readed ";
        $statement = $this->connection->prepare($query);

        $statement->bindValue(':receiver_id', $userId ?? Auth::user()->id);
        $statement->bindValue(':readed', 0);
        $statement->execute();
        $messagesRaw = $statement->fetch(PDO::FETCH_ASSOC);

        return intval($messagesRaw['unread_messages']);
    }

    /**
     * @param int $conversationId
     * @param int|null $userId
     * @return void
     */
    public function readMessages(int $conversationId, int $userId = null): void
    {
        $query = "UPDATE messages ";
        $query .= "SET readed = :readed ";
        $query .= "WHERE conversation_id = :conversation_id ";
        $query .= "AND receiver_id = :receiver_id ";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':readed', 1);
        $statement->bindValue(':receiver_id', $userId ?? Auth::user()->id);
        $statement->bindValue(':conversation_id', $conversationId);
        $statement->execute();
    }
}