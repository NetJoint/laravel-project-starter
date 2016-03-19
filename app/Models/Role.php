<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use App\Models\Traits\Listable;

class Role extends EloquentRole
{

    use Listable;

    protected $guarded = [
        'id', 'created_at'
    ];
    protected $columns = ['id', 'name', 'slug', 'permissions', 'created_at', 'updated_at'];

}
