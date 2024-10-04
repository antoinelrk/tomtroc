<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Enum\EnumNotificationState;
use App\Models\Conversation;
use App\Services\ConversationService;
use App\Services\MessagesService;
use App\Services\UserService;

class ConversationsController extends Controller
{
    /**
     * @param ConversationService $conversationService
     * @param MessagesService $messagesService
     * @param UserService $userService
     */
    public function __construct(
        protected ConversationService $conversationService = new ConversationService(),
        protected MessagesService $messagesService = new MessagesService(),
        protected UserService $userService = new UserService(),
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function index(): void
    {
        $conversation = $this->conversationService->getFirstConversation();

        if (empty($conversation)) {
            Response::redirect('/conversations/no-message');
        } else {
            Response::redirect('/conversations/show/' . $conversation->uuid);
        }
    }

    /**
     * @param string $uuid
     * @return mixed
     */
    public function show(string $uuid): mixed
    {
        $conversations = $this->conversationService->getConversations();

        $selectedConversation = array_values(array_filter($conversations, function (Conversation $conversation) use ($uuid) {
            return $conversation->uuid === $uuid;
        }));

        $this->messagesService->readMessages($selectedConversation[0]->id);

        return View::layout('layouts.app')
            ->withData([
                'conversations' => $conversations,
                'selectedConversation' => $selectedConversation[0],
            ])
            ->view('pages.conversations.index')
            ->render();
    }

    /**
     * @param int $userId
     * @return bool
     * @throws \Random\RandomException
     */
    public function create(int $userId): bool
    {
        if ($userId === Auth::user()->id) {
            Notification::push(
                'Vous ne pouvez pas vous envoyer un message à vous même !',
                EnumNotificationState::ERROR->value
            );
            Response::redirect('/conversations/show');

            return false;
        }

        $user = $this->userService->getUserById($userId);

        if ($user === null) {
            Notification::push(
                'L\'utilisateur n\'existe pas !',
                EnumNotificationState::ERROR->value
            );
            Response::redirect('/conversations/show');

            return false;
        }

        $conversations = $this->conversationService->getConversations();

        $likelyConversation = array_values(array_filter($conversations, function (Conversation $conversation) use ($userId) {
            return $conversation->relations['receiver']->id === $userId;
        }));

        if (!empty($likelyConversation)) {
            Response::redirect('/conversations/show/' . $likelyConversation[0]->uuid);
        }

        return View::layout('layouts.app')
            ->withData([
                'user' => $user,
                'conversations' => $conversations,
            ])
            ->view('pages.conversations.create')
            ->render();
    }

    /**
     * @return mixed
     */
    public function noMessage()
    {
        return View::layout('layouts.app')
            ->view('pages.conversations.no-message')
            ->render();
    }
}
