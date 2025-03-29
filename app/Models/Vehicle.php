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
        'driver_id',
        'reg_expire_date',
        'inspection_expire_date',
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

}
