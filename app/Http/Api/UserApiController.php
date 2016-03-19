<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\UserRoleRequest;
use App\Http\Transformers\UserTransformer;
use App\Http\Transformers\RoleTransformer;

class UserApiController extends ApiController
{

    public function __construct()
    {
        
    }

    public function getList(Request $request)
    {
        $items = User::processRequest($request);
        if ($items instanceof \Illuminate\Database\Eloquent\Collection) {
            return $this->response->collection($items, new UserTransformer);
        } else {
            return $this->response->paginator($items, new UserTransformer);
        }
    }

    public function typeahead(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 5);
        $filters = 'id,like,%' . $query . '%|name,like,%' . $query . '%';
        $response = $this->api->get('/user', ['limit' => $limit, 'filters' => $filters]);
        if (isset($response['status_code'])) {
            return $response;
        }
        $suggestions = [];
        foreach ($response->items() as $user) {
            $suggestions[] = [
                'value' => $user->name . '#' . $user->id,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ]
            ];
        }
        return $this->response->array(['query' => $query, 'suggestions' => $suggestions]);
    }

    public function getDetail($id)
    {
        $item = User::find($id);
        if (!$item) {
            return $this->response->errorNotFound('用户不存在');
        }
        return $this->response->item($item, new UserTransformer);
    }

    public function update(Request $request, $id)
    {
        if (is_int($id)) {
            $rs = User::processUpdateRequest($request, [['id', $id]]);
        } else {
            //支持批量修改
            $ids = explode(',', $id);
            $count = User::processUpdateRequest($request, [['id', 'in', $ids]]);
        }
        if($count){
            $message = "修改了{$count}个用户信息";
        }else{
            $message = "未修改用户信息";
        }
        return $this->response->array(['message' => $message]);
    }

    public function destroy($id)
    {
        if (is_int($id)) {
            $count = User::where('id', $id)->delete();
        } else {
            //支持批量删除
            $ids = explode(',', $id);
            $count = User::whereIn('id', $ids)->delete();
        }
        return $this->response->array(['message' => "{$count}条数据已删除"]);
    }
    
    public function getRole(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $items = $user->roles;
        return $this->response->collection($items, new RoleTransformer);
    }
    
    public function postRole(UserRoleRequest $request)
    {
        $role_id = $request->input('role_id');
        if(!$role_id){
            $slug = $request->input('slug');
            $role = Role::where('slug' , $slug)->first();
        }else{
            $role = Role::find($role_id);
        }
        if (!$role) {
            return $this->response->errorNotFound('角色不存在');
        }
        $user_id = (int) $request->input('user_id');
        $user = User::find($user_id);
        $exist = $user->roles()->find($role_id);
        if($exist){
            return $this->response->array(['message' => '用户已有该角色']);
        }
        $user->roles()->attach($role_id);
        $message = "给用户【{$user->name}#{$user->id}】添加角色【{$role->name}】";
        return $this->response->array(['message' => $message]);
    }

}
