<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\QueryBuilder;
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
        $book = (new Book())->where('slug', $slug)->first();

        return View::layout('layouts.app')
            ->view('pages.books.show')
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
