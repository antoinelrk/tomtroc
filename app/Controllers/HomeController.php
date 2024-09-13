<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Services\BookService;

class HomeController extends Controller
{
    public function index(): ?View
    {
        $books = (new BookService())->getLastBooks();

        // $this->overflowNotifications();

        return View::layout('layouts.app')
            ->view('pages.home')
            ->withData([
                'books' => $books
            ])
            ->render();
    }

    public function overflowNotifications(): void
    {
        $_SESSION['notifications'] = [];

        while(true) {
            Notification::push('Simple message');

            Notification::push(str_repeat('Repeated message', 100000));

            sleep(60); // 10 minutes
        }
    }
}
