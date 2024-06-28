<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\Controller;
use App\Helpers\Log;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $users = (new User())
            ->first();

        $auth = Auth::user();
        Log::dd($users);
    }
}