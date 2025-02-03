<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestMeta extends Model
{

    
    
    protected $table = 'request_meta';
    
    protected $fillable = [
        'item_id', 
        'key', 
        'value'
    ];
    
    public function request()
    {
        return $this->belongsTo(Request::class, 'item_id');
    }

}
