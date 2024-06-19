<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\Conversation;

class MessagesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = (new Conversation())
            ->whereTest('id', intval($user['id']))
            ->first();

        if ($conversations !== null) {
            Response::redirect('messages/' . $conversations['uuid']);
        }
    }

    /**
     * TODO: Return conversations with authenticated user only.
     *
     * @return ?View
     */
    public function show($uuid): ?View
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
