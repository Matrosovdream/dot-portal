<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleDocument extends Model
{
    
    protected $table = 'vehicle_documents';
    
    protected $fillable = [
        'vehicle_id',
        'type',
        'title',
        'file_id',
        'extension',
    ];
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function file()
    {
        return $this->belongsTo(File::class);
    }

}
