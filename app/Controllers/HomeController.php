<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Services\BookService;

class HomeController extends Controller
{
    public function index(): ?View
    {
        $books = (new BookService())->getLastBooks();

        return View::layout('layouts.app')
            ->view('pages.home')
            ->withData([
                'books' => $books
            ])
            ->render();
    }
}
