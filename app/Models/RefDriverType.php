<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefDriverType extends Model
{
    
    protected $table = 'ref_driver_type';
    
    protected $fillable = [
        'title',
        'slug',
        'is_published',
    ];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_type', 'driver_id', 'driver_type_id');
    }

}
