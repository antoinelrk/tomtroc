<?php
namespace App\Models;

use App\Core\Auth\Auth;
use App\Core\Model;
use App\Helpers\Log;
use PDO;

class Conversation extends Model
{
    protected string $table = 'conversations';

    public function __construct()
    {
        parent::__construct();
    }

    public function getConversationsNew()
    {
        $query = "SELECT ";
        $query .= "
                conversations.id AS conversation_id,
                conversations.uuid,
                conversations.owner_id,
                conversations.target_id,
                conversations.created_at,
                conversations.updated_at,
                    owner.username AS owner_username,
                    owner.display_name AS owner_display_name,
                    owner.avatar AS owner_avatar,
                    target.username AS target_username,
                    target.display_name AS target_display_name,
                    target.avatar AS target_avatar,
                messages.id AS message_id,
                messages.content,
                messages.created_at,
                messages.updated_at,
                messages.user_id,
                    message_user.username AS message_username,
                    message_user.display_name AS message_display_name,
                    message_user.avatar AS message_avatar
            FROM 
                conversations
            LEFT JOIN 
                users AS owner ON conversations.owner_id = owner.id
            LEFT JOIN 
                users AS target ON conversations.target_id = target.id
            LEFT JOIN 
                messages ON conversations.id = messages.conversation_id
            LEFT JOIN 
                users AS message_user ON messages.user_id = message_user.id
            WHERE conversations.owner_id = :owner_id OR conversations.target_id = :owner_id
            ORDER BY 
                conversations.updated_at DESC, messages.created_at ASC";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':owner_id', Auth::user()['id']);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $conversations = [];

        foreach ($results as $row) {
            $conversation_id = $row['conversation_id'];

            // Si la conversation n'existe pas encore dans le tableau, l'ajouter
            if (!isset($conversations[$conversation_id])) {
                $conversations[$conversation_id] = [
                    'id' => $row['conversation_id'],
                    'uuid' => $row['uuid'],
                    'users' => [
                        'owner' => [
                            'id' => $row['owner_id'],
                            'username' => $row['owner_username'],
                            'display_name' => $row['owner_display_name'],
                            'avatar' => $row['owner_avatar']
                        ],
                        'target' => [
                            'id' => $row['target_id'],
                            'username' => $row['target_username'],
                            'display_name' => $row['target_display_name'],
                            'avatar' => $row['target_avatar']
                        ]
                    ],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'messages' => []
                ];
            }

            // Ajouter le message à la conversation correspondante
            if ($row['message_id']) {
                $conversations[$conversation_id]['messages'][] = [
                    'id' => $row['message_id'],
                    'content' => $row['content'],
                    'user' => [
                        'id' => $row['user_id'],
                        'username' => $row['message_username'],
                        'avatar' => $row['message_avatar'],
                    ],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ];
            }
        }

        return $conversations;
    }

    public function messages(...$argv)
    {
        $model = 'messages';
        $mapped = array_map(function($element) use ($model) {
            return 'm.' . $element;
        }, $argv);

        $this->getInstance()->selectable .= ", " . implode(', ', $mapped);

        $this->getInstance()->query .= " INNER JOIN {$model} m ON {$this->table}.id = m.conversation_id ";
        return $this->getInstance();
    }

    public function users(...$argv)
    {
        $model = 'users';
        $pivot = 'conversation_user';

        $mapped = array_map(function($element) use ($model) {
            return $model . '.' . $element;
        }, $argv);

        $this->getInstance()->selectable .= ", " . implode(', ', $mapped);

        $this->getInstance()->query .= " JOIN {$pivot} ON {$this->table}.id = {$pivot}.conversation_id";

        $this->getInstance()->query .= " JOIN {$model} ON users.id = {$pivot}.user_id";

        return $this->getInstance();
    }

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