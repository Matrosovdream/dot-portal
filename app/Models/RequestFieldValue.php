<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFieldValue extends Model
{
    
    protected $table = 'request_field_values';

    public $timestamps = false;

    protected $fillable = [
        'request_id', 
        'field_id', 
        'value'
    ];
    
    public function request()
    {
        return $this->belongsTo(Request::class);
    }
    
    public function field()
    {
        return $this->belongsTo(ReferenceFormField::class);
    }

}
