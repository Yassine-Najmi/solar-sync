<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function services()
    {
        return view('services');
    }

    public function about()
    {
        return view('about');
    }
}
