<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverMeta extends Model
{
    
    protected $table = 'driver_meta';
    
    protected $fillable = [
        'item_id',
        'key',
        'value',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'item_id');
    }

}
