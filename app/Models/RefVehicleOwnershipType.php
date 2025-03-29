<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefVehicleOwnershipType extends Model
{
    
    protected $table = 'ref_vehicle_ownership_type';
    
    protected $fillable = [
        'name',
        'code',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'ownership_type_id');
    }

}
