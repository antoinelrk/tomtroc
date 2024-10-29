<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Core\Validator;
use App\Enum\EnumNotificationState;
use App\Helpers\Log;
use App\Services\BookService;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     * @param BookService $bookService
     */
    public function __construct(
        protected UserService $userService = new UserService(),
        protected BookService $bookService = new BookService()
    ) {
        parent::__construct();
    }

    /**
     * Return private profil page.
     *
     * @return void
     */
    public function me(): void
    {
        $user = Auth::user();

        $books = new BookService();
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

    /**
     * @param $username
     * @return void
     */
    public function show($id): void
    {
        $user = $this->userService->getUserById($id);
        $user->relations['books'] = $this->bookService->getUserBook($user);

        View::layout('layouts.app')
            ->withData([
                'user' => $user,
                'books' => $user->relations['books'],
            ])
            ->view('pages.users.profile')
            ->render();
    }

    /**
     * @param $userId
     * @return void
     * @throws \Random\RandomException
     */
    public function update($userId): void
    {
        $request = Validator::user();

        $user = $this->userService->getUserById($userId);

        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $request['avatar'] = $_FILES['avatar'];
        }

        if ($this->userService->update($user, $request)) {
            Notification::push(
                'Profil édité avec succès',
                EnumNotificationState::SUCCESS->value
            );
        } else {
            Notification::push(
                'Impossible de mettre à jour le profil, contactez un administrateur.',
                EnumNotificationState::ERROR->value
            );
        }

        Response::redirect('/me');
    }
}
