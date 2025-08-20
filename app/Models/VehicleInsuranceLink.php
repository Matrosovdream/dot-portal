<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\Vehicle\VehicleInsuranceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([VehicleInsuranceObserver::class])]
class VehicleInsuranceLink extends Model
{
    
    protected $table = 'vehicle_insurance_link';

    public $timestamps = false;

    protected $fillable = [
        'vehicle_id',
        'insurance_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function insurance()
    {
        return $this->belongsTo(InsuranceVehicle::class);
    }

}
