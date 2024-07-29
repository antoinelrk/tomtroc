<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\Conversation;
use App\Models\ConversationManager;

class ConversationsController extends Controller
{
    protected ConversationManager $conversationsManager;

    public function __construct()
    {
        parent::__construct();

        $this->conversationsManager = new ConversationManager();
    }

    public function index()
    {
        $conversation = $this->conversationsManager->getFirstConversation();

        if ($conversation !== null) {
            Response::redirect('conversations/' . $conversation->uuid);
        }
    }

    public function show(string $uuid)
    {
        $conversations = (new ConversationManager())->getConversations();

        $selectedConversation = array_filter($conversations, function (Conversation $conversation) use ($uuid) {
           return $conversation->uuid === $uuid;
        });

        return View::layout('layouts.app')
            ->withData([
                'conversations' => $conversations,
                'selectedConversation' => $selectedConversation[0],
            ])
            ->view('pages.conversations.index')
            ->render();
    }

    public function create($id)
    {
        /**
         * Il faut vérifier si la conversation existe en fonction de l'id de l'utilisateur, si elle existe on redirige
         * vers /conversation/uuid
         * Sinon on créé une nouvelle conversation et on redirige vers un formulaire.
         */
        if (intval($id) === Auth::user()->id || $id === null) {
            Response::redirect('/conversations');
        }

        $conversation = $this->conversationsManager->getConversationByUserId($id);

        if (!$conversation) {
            Log::dd(uniqid());
            /**
             * 1. On créé la nouvelle conversation
             * 2. On attache les utilisateurs à la conversation
             * 3. On redirige vers la page de conversation avec l'id
             */
        }


        Log::dd($conversation);
    }
}