<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Core\Validator;
use App\Enum\EnumNotificationState;
use App\Helpers\Log;
use App\Helpers\Str;
use App\Services\ConversationService;
use App\Services\MessagesService;

class MessagesController extends Controller
{
    /**
     * @param MessagesService $messagesManager
     * @param ConversationService $conversationManager
     */
    public function __construct(
        protected MessagesService $messagesManager = new MessagesService(),
        protected ConversationService $conversationManager = new ConversationService()
    ) {
        parent::__construct();
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function store(): void
    {
        $request = [
            'conversation_id' => filter_input(INPUT_POST, 'conversation_id', FILTER_VALIDATE_INT),
            'uuid' => filter_input(INPUT_POST, 'uuid', FILTER_SANITIZE_SPECIAL_CHARS),
            'receiver_id' => filter_input(INPUT_POST, 'receiver_id', FILTER_VALIDATE_INT),
            'content' => filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS),
        ];

        $isValid = Validator::check($request, [
            'conversation_id' => [
                'required' => true,
            ],
            'uuid' => [
                'required' => true,
            ],
            'receiver_id' => [
                'required' => true,
            ],
            'content' => [
                'required' => true,
            ]
        ]);

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

        $this->conversationManager->refresh($conversation->id ?? $request['conversation_id']);

        $uuid = $request['uuid'] ?? $conversation->uuid;

        Response::redirect('/conversations/show/' . $uuid);
    }
}
