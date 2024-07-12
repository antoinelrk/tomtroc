<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\QueryBuilder;
use App\Core\Response;
use App\Helpers\Log;
use App\Models\Book;
use App\Models\BookManager;
use App\Models\Conversation;
use App\Models\ConversationManager;
use App\Models\User;

class BooksController extends Controller
{
    /**
     * Return index page.
     *
     * @return View|null
     */
    public function index(): ?View
    {
        $books = (new BookManager())->getBooks();

        return View::layout('layouts.app')
            ->view('pages.books.index')
            ->withData([
                'books' => $books
            ])
            ->render();
    }

    public function show(string $slug)
    {
        $bookManager = new BookManager();
        $book = $bookManager->getBook($slug, false);

        return View::layout('layouts.app')
            ->view('pages.books.show')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function showEditForm($slug)
    {
        $bookManager = new BookManager();
        $book = $bookManager->getBook($slug, false);
        $bookUser = $book->relations['user'];

        if ($bookUser->id !== Auth::user()->id) {
            Notification::push('Vous n\'avez pas l\'authorisation de modifier ce livre !', 'error');
            Response::redirect('/books/' . $slug);
        }

        return View::layout('layouts.app')
            ->view('pages.books.edit')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function test()
    {
        $books = (new QueryBuilder())->table(Book::class)
            ->get()
            ->all();

        $users = (new QueryBuilder())
            ->table(User::class)
            ->get()
            ->all();

        Log::dd($users);
    }
}
