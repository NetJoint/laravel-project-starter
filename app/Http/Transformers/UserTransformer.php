<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(User $item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'mobile' => $item->mobile,
            'last_login' => (string) $item->last_login,
            'created_at' => (string) $item->created_at,
            'roles'=> $item->roles->pluck('name','slug')->toArray()
        ];
    }

}
