<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Config;

use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        
        dd(Post::all());
    }
}
