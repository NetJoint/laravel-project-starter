<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function getDetail(Request $request, $id)
    {  
        $id = (int) $id;
        $category  = Category::find($id);
        if(!$category){
            abort(404);
        }
        $documents = $this->api->get("category/{$id}/document");
        $data = compact('category','documents');
        return view('templates.'.$category->list_template, $data);
    }

}