<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    
    protected $table = 'driver_documents';
    
    protected $fillable = [
        'driver_id',
        'type',
        'title',
        'file_id',
        'extension',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
}
