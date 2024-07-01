<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\Conversation;

class ConversationsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();

        $conversations = (new Conversation())->all();

        Log::dd($conversations[0]->properties);
        $conversations = (new Conversation())
            ->users('display_name')
            ->whereTest('id', $user->id, 'users')
            ->orderBy('updated_at', 'DESC')
            ->first();

        if ($conversations !== null) {
            Response::redirect('conversations/' . $conversations['uuid']);
        }
    }

    public function show(string $uuid)
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
}