<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\Response;
use App\Core\Validator;
use App\Enum\EnumNotificationState;
use App\Helpers\Log;
use App\Services\BookService;

class BooksController extends Controller
{
    /**
     * @param BookService $bookService
     */
    public function __construct(
        protected BookService $bookService = new BookService(),
    ) {
        parent::__construct();
    }

    /**
     * @return View|null
     */
    public function index(): ?View
    {
        $filter = $_GET['title'] ?? null;

        $books = $this->bookService->getAvailableBooks($filter);

        return View::layout('layouts.app')
            ->view('pages.books.index')
            ->withData([
                'books' => $books
            ])
            ->render();
    }

    /**
     * @param string $slug
     * @return View|null
     */
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

    /**
     * @return View|null
     */
    public function create(): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.books.create')
            ->render();
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function store(): void
    {
        $request = [
            'title' => filter_input(INPUT_POST, 'title'),
            'author' => filter_input(INPUT_POST, 'author'),
            'description' => filter_input(INPUT_POST, 'description'),
            'available' => filter_input(INPUT_POST, 'available'),
        ];

        $data = [
            'title' => $request['title'],
            'author' => $request['author'],
            'description' => $request['description'],
            'available' => $request['available'] ? 1 : 0,
        ];

        $isValid = Validator::check($data , [
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

    /**
     * @param string $slug
     * @return View|null
     */
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

    /**
     * @param string $slug
     * @return void
     * @throws \Random\RandomException
     */
    public function update(string $slug): void
    {
        $request = [
            'title' => filter_input(INPUT_POST, 'title'),
            'author' => filter_input(INPUT_POST, 'author'),
            'description' => filter_input(INPUT_POST, 'description'),
            'cover' => filter_input(INPUT_POST, 'cover'),
            'available' => filter_input(INPUT_POST, 'available'),
        ];

        $slug = filter_var($slug, FILTER_SANITIZE_SPECIAL_CHARS);

        $book = $this->bookService->getBook($slug);

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

            Response::referer();
        } else {
            if ($_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
                $request['cover'] = $_FILES['cover'];

                $newBook = $this->bookService->update($book, $request);

                if (!is_bool($newBook)) {
                    Response::redirect('/books/show/' . $newBook->slug);
                } else {
                    Response::referer();
                }
            } else {
                Notification::push('BookController error', EnumNotificationState::ERROR->value);
                Response::referer();
            }
        }
    }

    /**
     * @param string $slug
     * @return void
     * @throws \Random\RandomException
     */
    public function delete(string $slug): void
    {
        $book = $this->bookService->getBook($slug);

        if (!$this->bookService->delete($book)) {
            Notification::push('Le livre n\'existe pas', EnumNotificationState::ERROR->value);
        } else {
            Notification::push('Le livre a bien été supprimé.', EnumNotificationState::SUCCESS->value);
        }

        Response::redirect('/me');
    }
}
