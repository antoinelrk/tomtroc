<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return View::layout('layouts.app')
            ->withData([
                'title' => 'Login',
            ])
            ->view('pages.auth.login')
            ->render();
    }
}
