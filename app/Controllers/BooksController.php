<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Helpers\Log;
use App\Helpers\Str;
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
        // $books = (new Book())->where('available', '=', '1');
        $books = (new Book())->getBooks();

        return View::layout('layouts.app')
            ->view('pages.books.index')
            ->withData([
                'books' => $books
            ])
            ->render();
    }

    public function show(string $slug): ?View
    {
        $book = (new Book())
            ->whereTest('slug', $slug)
            ->first();

        Log::dd($book);

        return View::layout('layouts.app')
            ->view('pages.books.show')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function create(): ?View
    {
        $slug = Str::slug("Bonjour à tous !");

        return $slug;
    }
}
