<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Facades\View;
use App\Core\Response;

class HomeController extends Controller
{
    public function index(): View
    {
        // TODO: WIP! Return ViewRenderer manager OR Http response manager (For api usage)

        return View::render('pages.home');
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
