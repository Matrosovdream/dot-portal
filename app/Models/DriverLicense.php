<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\Driver\DriverLicenseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([DriverLicenseObserver::class])]
class DriverLicense extends Model
{
    
    protected $table = 'driver_license';
    
    protected $fillable = [
        'driver_id',
        'type_id',
        'endorsement_id',
        'license_number',
        'expiration_date',
        'state_id',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function type()
    {
        return $this->belongsTo(RefDriverLicenseType::class, 'type_id');
    }

    public function endorsement()
    {
        return $this->belongsTo(RefDriverLicenseEndrs::class, 'endorsement_id');
    }

    public function countryState()
    {
        return $this->belongsTo(RefCountryStates::class, 'state_id');
    }

}
