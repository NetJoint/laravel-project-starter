<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Listable;

class Document extends Model
{
    use Listable;

    protected $guarded = [
        'id', 'created_at'
    ];
    protected $columns = ['id', 'user_id', 'category_id', 'title', 'thumb', 'description', 'content', 'link', 'hash', 'rank', 'created_at', 'updated_at'];

    public function publisher()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
