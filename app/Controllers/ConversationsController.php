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
use App\Models\Conversation;
use App\Services\ConversationService;
use App\Services\UserService;

class ConversationsController extends Controller
{
    protected ConversationService $conversationsManager;
    protected UserService $userManager;

    public function __construct()
    {
        parent::__construct();

        $this->conversationsManager = new ConversationService();
        $this->userManager = new UserService();
    }

    public function index()
    {
        $conversation = $this->conversationsManager->getFirstConversation();

        if ($conversation !== null) {
            Response::redirect('/conversations/show/' . $conversation->uuid);
        } else {
            Log::dd('aucun message');
        }
    }

    public function show(string $uuid)
    {
        $conversations = $this->conversationsManager->getConversations();

        if ($conversations)

        $selectedConversation = array_values(array_filter($conversations, function (Conversation $conversation) use ($uuid) {
           return $conversation->uuid === $uuid;
        }));

        return View::layout('layouts.app')
            ->withData([
                'conversations' => $conversations,
                'selectedConversation' => $selectedConversation[0],
            ])
            ->view('pages.conversations.index')
            ->render();
    }

    public function create(int $userId): bool
    {
        if ($userId === Auth::user()->id) {
            Notification::push(
                'Vous ne pouvez pas vous envoyer un message à vous même !',
                EnumNotificationState::ERROR->value
            );
            Response::redirect('/conversations/show');
        }

        $user = $this->userManager->getUserById($userId);

        if ($user === null) {
            Notification::push(
                'L\'utilisateur n\'existe pas !',
                EnumNotificationState::ERROR->value
            );
            Response::redirect('/conversations/show');

            return false;
        }

        // On récupère la liste des conversations
        $conversations = $this->conversationsManager->getConversations();

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

    public function store(): bool
    {
        $request = $_POST;

        $isValid = Validator::check($request, [
            'receiver_id' => [
                'required' => true,
            ],
            'content' => [
                'required' => true,
            ],
        ]);

        if ($isValid !== true) {
            Notification::push(
                'L\'utilisateur et le contenu sont nécessaires !',
                EnumNotificationState::ERROR->value
            );

            return false;
        }

        // $this->conversationsManager->createConversation($request);
        return false;
    }
}