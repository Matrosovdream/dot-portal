<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceField extends Model
{
    
    protected $table = 'service_fields';
    protected $fillable = [
        'field_id',
        'service_id',
        'entity',
        'section',
        'placeholder',
        'required',
        'default_value',
        'classes',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function field()
    {
        return $this->belongsTo(ServiceField::class);
    }

}
