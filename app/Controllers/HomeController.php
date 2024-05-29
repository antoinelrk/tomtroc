<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;

class HomeController extends Controller
{
    public function index(): ?View
    {
        return View::layout('layouts.app')
            ->view('pages.home')
            ->render();
    }
}
