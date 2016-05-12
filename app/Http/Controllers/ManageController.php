<?php

namespace App\Http\Controllers;

class ManageController extends Controller
{

    public function index()
    {
        $data = [];
        return view('manage.manage_index', $data);
    }
    
    public function category()
    {
        $data = [];
        return view('manage.manage_category', $data);
    }

    public function document()
    {
        $data = [];
        return view('manage.manage_document', $data);
    }

    public function user()
    {
        $data = [];
        return view('manage.manage_user', $data);
    }

}