<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Facades\View;
use App\Core\File;
use App\Core\File\Image;
use App\Core\Notification;
use App\Core\Response;
use App\Enum\EnumNotificationState;
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
        $books = $books->getUserBook(Auth::user());

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
        $user = $this->userManager->getUserByName($username)->books();

        View::layout('layouts.app')
            ->withData([
                'user' => $user,
                'books' => $user->relations['books'],
            ])
            ->view('pages.users.profile')
            ->render();
    }

    public function update($userId)
    {
        $user = $this->userManager->getUserById($userId);
        $request = $_POST;

        if($_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $request['avatar'] = $_FILES['avatar'];
        }

        $this->userManager->update($user, $request);

        Notification::push('Profil édité avec succès', EnumNotificationState::SUCCESS->value);
        Response::redirect('/me');
    }
}
