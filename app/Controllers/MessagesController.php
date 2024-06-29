<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Response;
use App\Models\Message;

class MessagesController extends Controller
{
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
