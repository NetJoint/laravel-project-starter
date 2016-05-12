<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Listable;

class Category extends Model
{

    use Listable;

    protected $guarded = [
        'id', 'created_at'
    ];
    protected $columns = ['id', 'parent_id', 'title','list_template','detail_template', 'rank', 'created_at', 'updated_at'];

    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'category_id');
    }
    
    public function parent_category()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }
    
    public function children_category()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

}
