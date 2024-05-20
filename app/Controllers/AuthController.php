<?php

namespace App\Controllers;

use App\Core\Facades\View;

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
