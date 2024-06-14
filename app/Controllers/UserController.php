<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Return list of users, just for API tests.
     *
     * @return void
     */
    public function index(): void
    {
        $users = (new User())->all();

        Response::json($users, Response::HTTP_OK);
    }

    /**
     * Return private profil page.
     * TODO: Passe auth-user data here..
     *
     * @return void
     */
    public function me(): void
    {
        View::layout('layouts.app')
            ->withData([
                'title' => 'Mon compte',
                'user' => Auth::user(),
            ])
            ->view('pages.me')
            ->render();
    }

    public function show($slug, $id): void
    {
        echo 'slug: ' . $slug;
        echo 'id: ' . $id;
    }
}
