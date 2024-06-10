<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Models\Conversation;

class MessagesController extends Controller
{
    /**
     * TODO: Return conversations with authenticated user only.
     *
     * @return ?View
     */
    public function index(): ?View
    {
        $user = Auth::user();
        $conversations = (new Conversation())->getConversations($user['id']);

        return View::layout('layouts.app')
            ->withData([
                'conversations' => $conversations,
            ])
            ->view('pages.messages.index')
            ->render();
    }
}
