<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Notification;

class HomeController extends Controller
{
    /**
     * Return home page.
     *
     * @return View|null
     */
    public function index(): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.home')
            ->render();
    }
}
