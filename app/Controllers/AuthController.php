<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Core\Validator;
use App\Helpers\Hash;
use App\Models\UserManager;

class AuthController extends Controller
{
    protected UserManager $userManager;

    public function __construct()
    {
        parent::__construct();

        $this->userManager = new UserManager();
    }

    public function loginForm(): ?View
    {
        return View::layout('layouts.app')
            ->withData([
                'title' => 'Login',
            ])
            ->view('pages.auth.login')
            ->render();
    }

    public function login(): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user = Auth::attempt($email, $password)) {
            Notification::push("Heureux de vous revoir $user->username !", 'success');
            Response::redirect('/me');
            exit;
        }

        Response::redirectToLogin();
    }

    public function registerForm(): ?View
    {
        return View::layout('layouts.app')
            ->withData([
                'title' => 'Register',
            ])
            ->view('pages.auth.register')
            ->render();
    }

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

        $displayName = ucfirst($request['username']);

        $this->userManager->create([
            'username' => $request['username'],
            'display_name' => $displayName,
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        Notification::push("Bienvenue sur TomTroc $displayName !", 'success');

        Auth::attempt($request['email'], $request['password']);

        Response::redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();

        Response::redirect('/auth/login');
    }
}
