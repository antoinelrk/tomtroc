<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Core\Validator;
use App\Helpers\Diamond;
use App\Helpers\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Login method.
     *  TODO: Add middleware for already authenticated users.
     *
     * @return ?View
     */
    public function loginForm(): ?View
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
     * @return ?View
     */
    public function registerForm(): ?View
    {
        return View::layout('layouts.app')
            ->withData([
                'title' => 'Register',
            ])
            ->view('pages.auth.register')
            ->render();
    }

    /**
     * Register a new user.
     *
     * @return void
     */
    public function register(): void
    {
        $request = $_POST;

        /*
         * TODO: Wip, Add checking password/confirmation and create new object for retrieve validatedData to push in
         * TODO: model creation.
         */
        $isValidate = Validator::check($request, [
            'username' => [
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
        ]);

        (new User())->create([
            'username' => $request['username'],
            'display_name' => ucfirst($request['username']),
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'created_at' => Diamond::now(),
            'updated_at' => Diamond::now(),
        ]);

        Response::redirect('/');
    }
}
