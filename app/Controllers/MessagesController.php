<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\Conversation;
use App\Models\Message;

class MessagesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = (new Conversation())
            ->users('display_name')
            ->whereTest('id', $user['id'], 'users')
            ->orderBy('updated_at', 'DESC')
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
        $conversations = (new Conversation())->getConversationsNew();

        // Si l'uuid n'est pas défini on retourne la premiere
        if (!$uuid) {
            $currentConversation = $conversations[0];
        } else {
            // Sur cette liste de conversation on récupère uniquement celle qui correspond à l'UUID passé en paramètre.

            $currentConversation = array_values(array_filter($conversations, function ($conversation) use ($uuid) {
                return $conversation['uuid'] === $uuid;
            }));
        }

        return View::layout('layouts.app')
            ->withData([
                'conversations' => $conversations,
                'currentConversation' => $currentConversation[0],
            ])
            ->view('pages.messages.index')
            ->render();
    }

    public function store()
    {
        $request = $_POST;

        (new Message())->create([
            'conversation_id' => $request['conversation_id'],
            'user_id' => Auth::user()['id'],
            'content' => $request['content'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Response::redirect('/messages/' . $request['uuid']);
    }
}
