<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class VehicleCrashSaferweb extends Model
{
    
    use Searchable;
    protected $table = "vehicle_crashes_saferweb";

    protected $fillable = [
        'vehicle_id',
        'unit_vin',
        'company_id',
        'dot_number',
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

    public function toSearchableArray()
    {
        return [
            'unit_vin'=> $this->unit_vin,
            'dot_number'=> $this->dot_number,
            'report_number'=> $this->report_number,
        ];
    }

    public function company()
    {
        return $this->belongsTo(UserCompany::class, 'company_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reportState()
    {
        return $this->belongsTo(RefCountryStates::class, 'report_state_id');
    }

}
