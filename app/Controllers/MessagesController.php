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
use App\Services\ConversationService;
use App\Services\MessagesService;

class MessagesController extends Controller
{
    public function __construct(
        protected MessagesService $messagesManager = new MessagesService(),
        protected ConversationService $conversationManager = new ConversationService()
    )
    {
        parent::__construct();
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
            $conversation = $this->conversationManager->create([
                'receiver_id' => $request['receiver_id'],
                'sender_id' => Auth::user()->id,
                'uuid' => Str::uuid(),
                'archived' => 0,
                'content' => $request['content'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->messagesManager->create([
            'conversation_id' => $conversation->id ?? $request['conversation_id'],
            'uuid' => $request['uuid'],
            'receiver_id' => $request['receiver_id'],
            'sender_id' => Auth::user()->id,
            'content' => $request['content'],
        ]);

        $uuid = $request['uuid'] ?? $conversation->uuid;

        Response::redirect('/conversations/show/' . $uuid);
    }
}
