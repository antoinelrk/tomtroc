<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Facades\View;
use App\Core\Response;

class HomeController extends Controller
{
    public function index(): View
    {
        Database::debug();

        return View::layout('layouts.app')
            ->view('pages.home')
            ->render();
    }

    /**
     * TODO: WIP! API Testing
     *
     * @return void
     */
    public function users(): void
    {
        $data = [
            'foo' => 'bar',
        ];

        Response::json($data, Response::HTTP_OK);
    }
}
