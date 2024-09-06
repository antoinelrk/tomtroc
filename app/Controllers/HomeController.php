<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;
use App\Enum\EnumNotificationState;
use App\Services\BookService;

class HomeController extends Controller
{
    public function index(): ?View
    {
        $books = (new BookService())->getLastBooks();
        Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);
        Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);
        Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);
        Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);
        Notification::push('Le poids de l\'image ne doit pas dépasser 5Mo', EnumNotificationState::ERROR->value);

        return View::layout('layouts.app')
            ->view('pages.home')
            ->withData([
                'books' => $books
            ])
            ->render();
    }
}
