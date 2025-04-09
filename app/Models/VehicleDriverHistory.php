<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleDriverHistory extends Model
{
    
    protected $table = 'vehicle_driver_history';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'start_date',
        'end_date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}
