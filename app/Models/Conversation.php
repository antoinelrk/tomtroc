<?php

namespace App\Models;

use App\Core\Model;
use App\Helpers\Log;
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
        $query = "SELECT ";
        $query .= "
            c.id AS conversation_id,
            c.uuid AS conversation_uuid,
            m.id AS message_id,
            m.content AS message_content,
            m.created_at AS message_created_at,
            u.id AS user_id,
            u.username AS user_name
        ";
        $query .= "FROM conversations c ";

        // On joint la pivot via l'ID des conversations
        $query .= "JOIN conversation_user cu ON c.id = cu.conversation_id ";

        // On joint les messages via l'ID des conversations
        $query .= "JOIN messages m ON c.id = m.conversation_id ";

        // On joint les utilisateurs via l'ID du message
        $query .= "JOIN users u ON m.user_id = u.id ";
        $query .= "WHERE cu.user_id = :user_id ";

        // TODO: Ajouter les filtres: UPDATED_AT pour la conversation et vérifier si la conversation n'est pas archivée.
        $query .= ";";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        $rawResults = $statement->fetchAll(PDO::FETCH_ASSOC);

        // ---------- FETCH ----------
        /**
         * Changer le système d'ID dans le tableau, trouver un moyen de vérifier si l'entrée existe sans utiliser
         * l'ID.
         */
        $conversations = [];

        foreach ($rawResults as $row) {
            $conversationId = $row['conversation_id'];

            if (!isset($conversations[$conversationId])) {
                $conversations[$conversationId] = [
                    'id' => $row['conversation_id'],
                    'uuid' => $row['conversation_uuid'],
                    'messages' => [],
                    'attendees' => [],
                ];
            }

            // On push le message dans la conversation courante:
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

        // Sortie:
        /*
         * $result = json_decode(json_encode($conversations));
         * Log::dd($result);
         */

        return array_values($conversations);
    }
}