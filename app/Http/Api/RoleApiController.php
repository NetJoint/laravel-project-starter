<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Transformers\RoleTransformer;

class RoleApiController extends ApiController
{

    public function __construct()
    {
        
    }

    public function getList(Request $request)
    {
        $items = Role::processRequest($request);
        if ($items instanceof \Illuminate\Database\Eloquent\Collection) {
            return $this->response->collection($items, new RoleTransformer);
        } else {
            return $this->response->paginator($items, new RoleTransformer);
        }
    }

    public function typeahead(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 5);
        $filters = 'id,like,%' . $query . '%|name,like,%' . $query . '%';
        $response = $this->api->get('/role', ['limit' => $limit, 'filters' => $filters]);
        if (isset($response['status_code'])) {
            return $response;
        }
        $suggestions = [];
        foreach ($response->items() as $item) {
            $suggestions[] = [
                'value' => $item->name,
                'data' => [
                    'id' => $item->id,
                    'name' => $item->name,
                ]
            ];
        }
        return $this->response->array(['query' => $query, 'suggestions' => $suggestions]);
    }

    public function getDetail($id)
    {
        $item = Role::find($id);
        if (!$item) {
            return $this->response->errorNotFound('角色不存在');
        }
        return $this->response->item($item, new RoleTransformer);
    }

    public function update(Request $request, $id)
    {
        if (is_int($id)) {
            $rs = Role::processUpdateRequest($request, [['id', $id]]);
        } else {
            //支持批量修改
            $ids = explode(',', $id);
            $count = Role::processUpdateRequest($request, [['id', 'in', $ids]]);
        }
        return $this->response->array(['message' => "{$count}条数据已修改"]);
    }

    public function destroy($id)
    {
        if (is_int($id)) {
            $count = Role::where('id', $id)->delete();
        } else {
            //支持批量删除
            $ids = explode(',', $id);
            $count = Role::whereIn('id', $ids)->delete();
        }
        return $this->response->array(['message' => "{$count}条数据已删除"]);
    }
    
    public function getRole(Request $request, $user_id)
    {
        $user = Role::find($user_id);
        $items = $user->roles;
        return $this->response->collection($items, new RoleTransformer);
    }

}
