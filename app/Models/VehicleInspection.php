<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInspection extends Model
{
    
    protected $table = 'vehicle_inspections';

    protected $fillable = [
        'vehicle_id',
        'inspection_date',
        'inspection_number',
        'file_id',
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
