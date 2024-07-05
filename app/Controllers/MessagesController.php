<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessagesManager;

class MessagesController extends Controller
{
    protected MessagesManager $messagesManager;

    public function __construct()
    {
        parent::__construct();

        $this->messagesManager = new MessagesManager();
    }

    public function store()
    {
        $request = $_POST;

        // TODO: Vérifier les données à envoyer dans un validateur

        $message = $this->messagesManager->create(
            [
                'conversation_id' => $request['conversation_id'],
                'user_id' => Auth::user()->id,
                'receiver_id' => $request['receiver_id'],
                'content' => $request['content'],
            ]
        );

        Response::redirect('/conversations/' . $request['uuid']);
    }
}
