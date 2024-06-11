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
        $query = "SELECT * ";
        $query .= "FROM conversations ";
        $query .= ";";

        $statement = $this->connection->prepare($query);
        //$statement->bindValue(':user_id', $userId);
        $statement->execute();
        $rawResults = $statement->fetch(PDO::FETCH_ASSOC);

        Log::dd($rawResults);

        return array_values($conversations);
    }
}