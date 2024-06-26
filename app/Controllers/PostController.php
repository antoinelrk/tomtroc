<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Log;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = (new Post())->only('id', 'title')->first();
        Log::dd($posts);
    }
}