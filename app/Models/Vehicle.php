<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    
    protected $table = 'vehicles';
    
    protected $fillable = [
        'unit_type_id',
        'number',
        'vin',
        'ownership_type_id',
        'reg_expire_date',
        'inspection_expire_date',
        'profile_photo_id',
        'driver_id',
        'company_id',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
    public function unitType()
    {
        return $this->belongsTo(RefVehicleUnitType::class, 'unit_type_id');
    }
    
    public function ownershipType()
    {
        return $this->belongsTo(RefVehicleOwnershipType::class, 'ownership_type_id');
    }
    
    public function documents()
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public function profilePhoto()
    {
        return $this->belongsTo(File::class, 'profile_photo_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function mvr()
    {
        return $this->hasOne(VehicleMvr::class);
    }

    public function insurance()
    {
        return $this->hasMany(VehicleInsuranceLink::class);
    }

    public function inspections()
    {
        return $this->hasMany(VehicleInspection::class);
    }

    public function driverHistory()
    {
        return $this->hasMany(VehicleDriverHistory::class);
    }

}
