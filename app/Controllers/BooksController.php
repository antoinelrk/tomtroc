<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Helpers\Log;
use App\Models\Book;

class BooksController extends Controller
{
    /**
     * Return index page.
     *
     * @return View|null
     */
    public function index(): ?View
    {
        $books = (new Book())
            ->available()
            ->all();

        return View::layout('layouts.app')
            ->view('pages.books.index')
            ->withData([
                'books' => $books
            ])
            ->render();
    }
}
