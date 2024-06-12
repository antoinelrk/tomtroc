<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
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
        $books = (new Book())->where('available', '=', '1');

        return View::layout('layouts.app')
            ->view('pages.books.index')
//            ->withData([
//                'books' => $books
//            ])
            ->render();
    }
}
