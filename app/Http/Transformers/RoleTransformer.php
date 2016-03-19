<?php

namespace App\Http\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(Role $item)
    {
        return [
            'id' => $item->id,
            'slug' => $item->slug,
            'name' => $item->name
        ];
    }

}
