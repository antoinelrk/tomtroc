<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Helpers\Log;
use App\Models\BookManager;

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

    public function show(string $slug): ?View
    {
        $book = (new BookManager())->getBook($slug);

        return View::layout('layouts.app')
            ->view('pages.books.show')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function createForm(): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.books.createForm')
            ->render();
    }

    public function edit(string $slug): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.books.edit')
            ->render();
    }
}
