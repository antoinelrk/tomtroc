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
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $conversation = (new ConversationManager())->getFirstConversation();

        if ($conversation !== null) {
            Response::redirect('conversations/' . $conversation->uuid);
        }
    }

    public function show(string $uuid)
    {
        $conversations = (new ConversationManager())->getConversations();

        $conversation = array_filter($conversations, function (Conversation $conversation) use ($uuid) {
           return $conversation->uuid === $uuid;
        });

        // Si l'uuid n'est pas défini on retourne la premiere
        if (!$conversation) {
            $currentConversation = $conversations[0];
        } else {
            $currentConversation = $conversation;
        }

        return View::layout('layouts.app')
            ->withData([
                'conversations' => $conversations,
                'currentConversation' => $currentConversation,
            ])
            ->view('pages.messages.index')
            ->render();
    }
}