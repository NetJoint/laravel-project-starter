<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{

    public function index()
    {
        $data = [];
        return view('welcome', $data);
    }

}