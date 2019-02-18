<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class IndexController extends Controller
{

    /**
     * Show vue app
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
    }
}
