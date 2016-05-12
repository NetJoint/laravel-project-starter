<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    public function index()
    {
        $data = [];
        return view('home.home_index', $data);
    }

}