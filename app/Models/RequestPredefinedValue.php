<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPredefinedValue extends Model
{

    protected $table = 'request_predefined_values';
    
    protected $fillable = [
        'request_id', 
        'field_code', 
        'value'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

}
