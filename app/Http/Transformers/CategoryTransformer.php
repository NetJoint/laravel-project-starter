<?php

namespace App\Http\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(Category $item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'rank' => $item->rank,
            'created_at' => (string) $item->created_at,            
            'doc_count' => $item->documents->count(),
            'list_template' => $item->list_template,
            'detail_template' => $item->detail_template,
            'parent_category' => $item->parent_category?$item->parent_category->title:'',
            'children_category' => $item->children_category->pluck('title')->toArray()
        ];
    }

}
