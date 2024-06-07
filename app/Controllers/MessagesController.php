<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;

class MessagesController extends Controller
{
    /**
     * TODO: Return conversations with authenticated user only.
     *
     * @return ?View
     */
    public function index(): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.messages.index')
            ->render();
    }
}
