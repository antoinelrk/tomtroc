<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Core\Validator;
use App\Enum\EnumNotificationState;
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

    public function index(): ?View
    {
        $books = $this->bookManager->getBooks(true);

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
            if ($this->bookManager->create($data)) {
                Notification::push(
                    'Votre nouveau livre a été ajouté',
                    EnumNotificationState::SUCCESS->value
                );

                Response::redirect('/books/show/' . $data['slug']);
            } else {
                Notification::push('Une erreur est survenue', EnumNotificationState::ERROR->value);

                Response::redirect('/books/create');
            }
        }

        Response::redirect('/books/create');
    }

    public function edit(string $slug): ?View
    {
        $book = $this->bookManager->getBook($slug);

        return View::layout('layouts.app')
            ->view('pages.books.edit')
            ->withData([
                'book' => $book
            ])
            ->render();
    }

    public function update(string $slug): void
    {
        $book = $this->bookManager->getBook($slug);
        $request = $_POST;

        if ($_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
            $request['cover'] = $_FILES['cover'];
        }

        if ($this->bookManager->update($book, $request)) {
            Notification::push('Livre édité avec succès', 'success');
        } else {
            Notification::push('Impossible de modifier la ressource, contactez un administrateur', EnumNotificationState::ERROR->value);
        }

        Response::redirect('/books/show/' . $slug);
    }

    public function delete(string $slug)
    {
        $book = $this->bookManager->getBook($slug);
        if ($this->bookManager->delete($book)) {
            Notification::push('Le livre n\'existe pas', EnumNotificationState::ERROR->value);
            Response::redirect('/me');
        }
    }
}
