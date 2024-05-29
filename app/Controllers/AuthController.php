<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Core\Validator;

class AuthController extends Controller
{
    /**
     * Login method.
     *  TODO: Add middleware for already authenticated users.
     *
     * @return View
     */
    public function loginForm(): View
    {
        return View::layout('layouts.app')
            ->withData([
                'title' => 'Login',
            ])
            ->view('pages.auth.login')
            ->render();
    }

    /**
     * Registering method.
     * TODO: Add middleware for already authenticated users.
     *
     * @return View
     */
    public function registerForm(): View
    {
        return View::layout('layouts.app')
            ->withData([
                'title' => 'Register',
            ])
            ->view('pages.auth.register')
            ->render();
    }

    /**
     * Create a new user
     *
     * @return void
     */
    public function register(): void
    {
        $isValidate = Validator::check($_POST, [
            'username' => [
                'string' => true,
                'required' => true,
            ],
            'displayName' => [
                'string' => true,
                'required' => true,
            ],
            'password' => [
                'string' => true,
                'required' => true,
            ],
            'email' => [
                'email' => true,
                'required' => true,
            ],
            // TODO: Add password_confirmation verification.
        ]);
//
//        Log::dd($result);
//
//        $data = [
//            'username' => 'kiwodd',
//            'display_name' => 'Kiwodd',
//            'email' => 'contact@kiwodd.com',
//            'password' => Hash::make('P@ss1234'),
//            'created_at' => Charbon::now(),
//            'updated_at' => Charbon::now(),
//        ];
//
//        $result = (new User())->create($data);
//
//        $data = [
//            'foo' => 'bar',
//        ];

        Response::redirect('/');
    }
}
