<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Listable;

class Attachment extends Model
{

    use Listable;

    protected $guarded = [
        'id', 'created_at'
    ];
    protected $columns = ['title', 'user_id', 'bytes', 'path', 'created_at', 'updated_at'];
   
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
