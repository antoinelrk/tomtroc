<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Helpers\Log;
use App\Models\BookManager;

class BooksController extends Controller
{
    public function __construct(
        protected BookManager $bookManager = new BookManager(),
    )
    {
        parent::__construct();
    }

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
        $book = $this->bookManager->getBook($slug);

        return View::layout('layouts.app')
            ->view('pages.books.show')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function create(): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.books.create')
            ->render();
    }

    public function store()
    {
        $data = [
            'title' => $_POST['title'],
            'author' => $_POST['author'],
            'description' => $_POST['description'],
            'available' => $_POST['available'] ? 1 : 0,
        ];

        $this->bookManager->create($data);
    }

    public function edit(string $slug): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.books.edit')
            ->render();
    }
}
