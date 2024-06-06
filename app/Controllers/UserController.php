<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Return list of users, just for API tests.
     *
     * @return void
     */
    public function index(): void
    {
        $users = (new User())->all();

        Response::json($users, Response::HTTP_OK);
    }
}
