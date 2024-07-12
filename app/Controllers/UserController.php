<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;
use App\Helpers\Errors;
use App\Helpers\Log;
use App\Models\Book;
use App\Models\BookManager;
use App\Models\User;
use App\Models\UserManager;

class UserController extends Controller
{
    protected UserManager $userManager;

    public function __construct()
    {
        parent::__construct();

        $this->userManager = new UserManager();
    }

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
     *
     * @return void
     */
    public function me(): void
    {

        $user = Auth::user();

        $books = new BookManager();
        $books = $books->getUserBook(false);

        View::layout('layouts.app')
            ->withData([
                'title' => 'Mon compte',
                'user' => $user,
                'books' => $books,
                'quantity' => count($books),
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

    public function update($userId)
    {
        $user = $this->userManager->getUserById($userId);
        $request = $_POST;

        $this->userManager->update($user, $request);

        Response::redirect('/me');
    }

    public function updateAvatar()
    {
        $request = $_POST;
        Log::dd($request);
    }
}
