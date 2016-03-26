<?php

namespace App\Http\Controllers\Api;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentCreateRequest;
use App\Http\Transformers\DocumentListTransformer;
use App\Http\Transformers\DocumentDetailTransformer;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class DocumentApiController extends ApiController
{

    public function __construct()
    {
        
    }

    public function getList(Request $request)
    {
        $items = Document::processRequest($request);
        if ($items instanceof \Illuminate\Database\Eloquent\Collection) {
            return $this->response->collection($items, new DocumentListTransformer);
        } else {
            return $this->response->paginator($items, new DocumentListTransformer);
        }
    }

    public function typeahead(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 10);
        $filters = 'id,like,%' . $query . '%|title,like,%' . $query . '%';
        $response = $this->api->get('/document', ['limit' => $limit, 'filters' => $filters]);
        if (isset($response['status_code'])) {
            return $response;
        }
        $suggestions = [];
        foreach ($response->items() as $item) {
            $suggestions[] = [
                'value' => $item->title . "(#{$item->id})",
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
        $item = Document::find($id);
        if (!$item) {
            return $this->response->errorNotFound('文档不存在');
        }
        return $this->response->item($item, new DocumentDetailTransformer);
    }

    public function store(DocumentCreateRequest $request)
    {
        $item = $request->all();
        $user = Sentinel::getUser();
        $item['user_id'] = $user->id;
        $item['hash'] = md5(uniqid());
        if ( !isset($item['link']) || $item['link'] == '') {
            $item['link'] = '/document/' . $item['hash'];
        }
        $document = Document::create($item);
        return $this->response->item($document, new DocumentListTransformer);
    }

    public function import(Request $request)
    {
        $items = $request->json();
        if (!$items || count($items) == 0) {
            return $this->response->errorBadRequest('没有文档导入');
        }
        foreach ($items as $item) {
            $item['user_id'] = $user->id;
            $item['hash'] = md5(uniqid());
            if ($item['link'] == '') {
                $item['link'] = '/document/' . $item['hash'];
            }
            Document::create($item);
        }
        return $this->response->array(['message' => '文档导入完毕']);
    }

    public function update(Request $request, $id)
    {
        if (is_int($id)) {
            //单行修改
            $count = Document::processUpdateRequest($request, [['id', $id]]);
        } else {
            //批量修改
            $ids = explode(',', $id);
            $count = Document::processUpdateRequest($request, [['id', 'in', $ids]]);
        }
        if ($count) {
            $message = "修改了{$count}个文档";           
        }
        return $this->response->array(['message' => $message]);
    }

    public function destroy(Request $request, $id)
    {
        if (is_int($id)) {
            //单行删除
            $count = Document::where('id', $id)->delete();
        } else {
            //批量删除
            $ids = explode(',', $id);
            $count = Document::whereIn('id', $ids)->delete();
        }
        if ($count) {
            $message = "删除了{$count}个文档";
            operation_log($request, $message . ': #' . $id);
        } else {
            $message = "没有删除文档";
        }
        return $this->response->array(['message' => "$message"]);
    }

}
