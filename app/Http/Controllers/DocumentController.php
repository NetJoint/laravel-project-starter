<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DocumentController extends Controller
{

    public function getDetail($hash)
    {
        $document = Document::where('hash',$hash)->first();
        if(!$document){
            abort(404);
        }
        $category  = $document->category;        
        $data = compact('document','category');
        return view('templates.'.$document->category->detail_template, $data);
    }

}