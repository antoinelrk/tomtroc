<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Errors;
use App\Helpers\Log;
use App\Models\Book;
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
        $relatedBooks = (new Book())
            ->users(
                'display_name',
                'avatar'
            )
            ->whereTest('user_id', Auth::user()['id'])
            ->get();

        View::layout('layouts.app')
            ->withData([
                'title' => 'Mon compte',
                'user' => Auth::user(),
                'books' => $relatedBooks,
                'quantity' => count($relatedBooks),
            ])
            ->view('pages.me')
            ->render();
    }

    public function show($username)
    {
        $user = (new User())->whereTest('username', $username)->first();

        $relatedBooks = (new Book())
            ->users(
                'display_name',
                'avatar'
            )
            ->whereTest('user_id', $user['id'])
            ->get();

        if ($user) {
            View::layout('layouts.app')
                ->withData([
                    'user' => $user,
                    'books' => $relatedBooks,
                ])
                ->view('pages.users.profile')
                ->render();
        } else {
            return Errors::notFound();
        }
    }
}
