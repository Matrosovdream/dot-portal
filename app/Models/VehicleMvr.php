<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMvr extends Model
{
    
    protected $table = 'vehicle_mvr';

    protected $fillable = [
        'vehicle_id',
        'mvr_number',
        'mvr_date',
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
