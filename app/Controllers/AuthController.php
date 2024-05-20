<?php

namespace App\Controllers;

use App\Core\Facades\View;

class AuthController
{
    public function login(): View
    {
        return View::layout('layouts.app')
            ->view('pages.auth.login')
            ->withData([
                'title' => 'Login'
            ])
            ->render();
    }
}
