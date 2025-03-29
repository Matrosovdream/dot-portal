<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefVehicleUnitType extends Model
{
    
    protected $table = 'ref_vehicle_unit_type';

    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'code',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'unit_type_id');
    }

}
