<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverAddress extends Model
{
    
    protected $table = 'driver_address';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = [
        'item_id',
        'address1',
        'address2',
        'city',
        'state_id',
        'zip',
        'country_id',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function countryState()
    {
        return $this->belongsTo(RefCountryStates::class, 'state_id');
    }

}
