<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Transformers\CategoryTransformer;
use App\Http\Transformers\DocumentListTransformer;

class CategoryApiController extends ApiController
{

    public function __construct()
    {
        
    }

    public function getList(Request $request)
    {
        $items = Category::processRequest($request);
        if ($items instanceof \Illuminate\Database\Eloquent\Collection) {
            return $this->response->collection($items, new CategoryTransformer);
        } else {
            return $this->response->paginator($items, new CategoryTransformer);
        }
    }
    
    public function typeahead(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 5);
        $filters = 'id,like,%' . $query . '%|title,like,%' . $query . '%';
        $response = $this->api->get('/category', ['limit' => $limit, 'filters' => $filters]);
        if (isset($response['status_code'])) {
            return $response;
        }
        $suggestions = [];
        foreach ($response->items() as $item) {            
            $suggestions[] = [
                'value' => $item->title."(#{$item->id})",
                'data' => [
                    'id' => $item->id,
                    'title' => $item->title
                ]
            ];
        }
        return $this->response->array(['query' => $query, 'suggestions' => $suggestions]);
    }

    public function getDetail($id)
    {
        $item = Category::find($id);
        if (!$item) {
            return $this->response->errorNotFound('栏目不存在');
        }
        return $this->response->item($item, new CategoryTransformer);
    }

    public function store(CategoryCreateRequest $request)
    {
        
        $data = $request->all();
        $category = Category::create($data);
        return $this->response->item($category, new CategoryTransformer);
    }

    public function update(Request $request, $id)
    {
        if (is_int($id)) {
            $count = Category::processUpdateRequest($request, [['id', $id]]);
        } else {
            //支持批量修改
            $ids = explode(',', $id);
            $count = Category::processUpdateRequest($request, [['id', 'in', $ids]]);
        }
        if($count){
            $message = "修改了{$count}个栏目";
        }
        return $this->response->array(['message' => $message]);
    }

    public function destroy(Request $request, $id)
    {   
        if (is_int($id)) {
            $count = Category::where('id', $id)->delete();
        } else {
            //支持批量删除
            $ids = explode(',', $id);
            $count = Category::whereIn('id', $ids)->delete();
        }
        if($count){
            $message = "删除了{$count}个栏目";
        }
        return $this->response->array(['message' => $message]);
    }
       
    public function getDocument(Request $request, $category_id)
    {
        $items = Document::processRequest($request, null, ['category'=>[['category_id',$category_id]]]);
        if ($items instanceof \Illuminate\Database\Eloquent\Collection) {
            return $this->response->collection($items, new DocumentListTransformer);
        } else {
            return $this->response->paginator($items, new DocumentListTransformer);
        }
    }
    
    public function deleteDocument(Request $request, $category_id, $document_id)
    {
        return $this->api->delete('document/'.$document_id);
    }

}
