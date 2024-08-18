<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Core\Validator;
use App\Enum\EnumNotificationState;
use App\Services\BookService;

class BooksController extends Controller
{
    public function __construct(
        protected BookService $bookService = new BookService(),
    )
    {
        parent::__construct();
    }

    public function index(): ?View
    {
        $books = $this->bookService->getBooks(true);

        return View::layout('layouts.app')
            ->view('pages.books.index')
            ->withData([
                'books' => $books
            ])
            ->render();
    }

    public function show(string $slug): ?View
    {
        $book = $this->bookService->getBook($slug);

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

    public function store(): void
    {
        $data = [
            'title' => $_POST['title'],
            'author' => $_POST['author'],
            'description' => $_POST['description'],
            'available' => $_POST['available'] ? 1 : 0,
        ];

        $isValid = Validator::check($data, [
            'title' => [
                'required' => true,
                'min' => 2,
                'max' => 128,
            ],
            'author' => [
                'required' => true,
                'min' => 2,
                'max' => 32,
            ],
            'description' => [
                'required' => true,
                'min' => 2,
            ],
            'cover' => [
                'required' => false,
            ]
        ]);

        if ($isValid) {
            $book = $this->bookService->create($data);

            if ($book === false) {
                Notification::push('Une erreur est survenue', EnumNotificationState::ERROR->value);

                Response::redirect('/books/create');
            } else {
                Notification::push(
                    'Votre nouveau livre a été ajouté',
                    EnumNotificationState::SUCCESS->value
                );

                Response::redirect('/books/show/' . $book->slug);
            }
        }
    }

    public function edit(string $slug): ?View
    {
        $book = $this->bookService->getBook($slug);

        return View::layout('layouts.app')
            ->view('pages.books.edit')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function update(string $slug): void
    {
        $book = $this->bookService->getBook($slug);
        $request = $_POST;

        $isValid = Validator::check($request, [
            'title' => [
                'required' => true,
                'min' => 2,
                'max' => 128,
            ],
            'author' => [
                'required' => true,
                'min' => 2,
                'max' => 32,
            ],
            'description' => [
                'required' => true,
                'min' => 2,
            ],
            'cover' => [
                'required' => false,
            ]
        ]);

        if (!$isValid) {
            Notification::push(
                'Certaines informations ne sont pas valides',
                EnumNotificationState::ERROR->value
            );

            Response::redirect('/register');
        }

        if ($_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
            $request['cover'] = $_FILES['cover'];
        }

        if ($this->bookService->update($book, $request)) {
            Notification::push('Livre édité avec succès', 'success');
        } else {
            Notification::push('Impossible de modifier la ressource, contactez un administrateur', EnumNotificationState::ERROR->value);
        }

        Response::redirect('/books/show/' . $slug);
    }

    public function delete(string $slug): void
    {
        $book = $this->bookService->getBook($slug);
        if ($this->bookService->delete($book)) {
            Notification::push('Le livre n\'existe pas', EnumNotificationState::ERROR->value);
            Response::redirect('/me');
        }
    }
}
