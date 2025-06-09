<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInspectionSaferweb extends Model
{
    
    protected $table = "vehicle_inspections_saferweb";

    protected $fillable = [
        'vehicle_id',
        'unique_id',
        'report_date',
        'report_number',
        'report_sequence_number',
        'inspection_level',
        'report_state',
        'report_state_id',
        'api_data'
    ];

    protected $casts = [
        'api_data' => 'array',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function inspectionState()
    {
        return $this->belongsTo(RefCountryStates::class, 'report_state_id');
    }

}
