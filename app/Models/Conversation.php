<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Conversation extends Model
{
    protected string $table = 'conversations';

    /**
     * TODO: WIP!
     *
     * @param $userId
     *
     * @return false|array
     */
    public function getConversations($userId): false|array
    {
        $query = "SELECT
                    c.id AS conversation_id,
                    m.id AS message_id,
                    m.content AS message_content,
                    m.created_at AS message_created_at,
                    u.id AS user_id,
                    u.username AS user_name
                FROM
                    conversations c
                JOIN
                    conversation_user cu ON c.id = cu.conversation_id
                JOIN
                    messages m ON c.id = m.conversation_id
                JOIN
                    users u ON m.user_id = u.id
                WHERE
                    cu.user_id = :user_id
                ORDER BY
                    c.id,
                    m.created_at";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        $rawResults = $statement->fetchAll(PDO::FETCH_ASSOC);

        $conversations = [];

        foreach ($rawResults as $row) {
            $conversationId = $row['conversation_id'];

            if (!isset($conversations[$conversationId])) {
                $conversations[$conversationId] = [
                    'id' => $row['conversation_id'],
                    'messages' => []
                ];
            }

            $conversations[$conversationId]['messages'][] = [
                'id' => $row['message_id'],
                'content' => $row['message_content'],
                'created_at' => $row['message_created_at'],
                'user' => [
                    'id' => $row['user_id'],
                    'username' => $row['user_name']
                ]
            ];
        }

        return array_values($conversations);
    }
}