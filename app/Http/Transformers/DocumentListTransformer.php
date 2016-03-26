<?php

namespace App\Http\Transformers;

use App\Models\Document;
use League\Fractal\TransformerAbstract;

class DocumentListTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(Document $item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'thumb' => $item->thumb,
            'link' => $item->link,
            'publisher' => $item->publisher->name,           
            'rank' => $item->rank,
            'created_at' => (string) $item->created_at,
            'updated_at' => (string) $item->updated_at
        ];
    }

}
