<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Enum\EnumNotificationState;
use App\Helpers\Log;
use App\Helpers\Str;
use App\Models\ConversationService;
use App\Models\MessagesService;

class MessagesController extends Controller
{
    protected MessagesService $messagesManager;
    protected ConversationService $conversationManager;

    public function __construct()
    {
        parent::__construct();

        $this->messagesManager = new MessagesService();
        $this->conversationManager = new ConversationService();
    }

    public function store()
    {
        $request = $_POST;

        $isValid = [
            'receiver_id' => [
                'required' => true,
            ]
        ];

        if (!$isValid) {
            Notification::push(
                'Le contact cible n\'existe pas !',
                EnumNotificationState::ERROR->value
            );

            Response::referer();
            return;
        }

        if (!isset($request['conversation_id']) && !isset($request['uuid'])) {
            // On créé la conversation

            $conversation = $this->conversationManager->create([
                'receiver_id' => $request['receiver_id'],
                'sender_id' => Auth::user()->id,
                'uuid' => Str::uuid(),
                'archived' => 0,
                'content' => $request['content'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            Log::dd($conversation);
        }

        $this->messagesManager->create([
            'conversation_id' => $request['conversation_id'],
            'uuid' => $request['uuid'],
            'receiver_id' => $request['receiver_id'],
            'sender_id' => Auth::user()->id,
            'content' => $request['content'],
        ]);
        // On créé juste le message

        Log::dd($_POST);

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

    /**
     * Faire la liste de tous les messages dont je suis le receveur et l'envoyer.
     * De chaque message, j'ai besoin de la relation du receveur et de l'envoyer (User)
     * Je dois grouper ces messages en fonction de l'utilisateur qui n'est pas moi: 'conversation'
     *
     */
}
