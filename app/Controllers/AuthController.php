<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Core\Validator;
use App\Helpers\Diamond;
use App\Helpers\Hash;
use App\Helpers\Log;
use App\Models\User;
use App\Models\UserManager;

class AuthController extends Controller
{
    protected UserManager $userManager;

    public function __construct()
    {
        parent::__construct();

        $this->userManager = new UserManager();
    }
    /**
     * Login method.
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
     * Login a user.
     *
     * @return void
     */
    public function login(): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (Auth::attempt($email, $password)) {
            Response::redirect('/me');
            exit;
        }

        Response::redirectToLogin();
    }

    /**
     * Registering method.
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

        $this->userManager->create([
            'username' => $request['username'],
            'display_name' => ucfirst($request['username']),
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        Response::redirect('/');
    }

    /**
     * Log out the user and redirect to log in form.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();

        Response::redirect('/auth/login');
    }
}
