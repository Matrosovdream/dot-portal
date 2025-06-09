<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCrashSaferweb extends Model
{
    
    protected $table = "vehicle_crashes_saferweb";

    protected $fillable = [
        'vehicle_id',
        'report_date',
        'report_number',
        'report_sequence_number',
        'report_state',
        'report_state_id',
        'total_injuries',
        'total_fatalities',
        'api_data'
    ];

    protected $casts = [
        'api_data' => 'array',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reportState()
    {
        return $this->belongsTo(RefCountryStates::class, 'report_state_id');
    }

}
