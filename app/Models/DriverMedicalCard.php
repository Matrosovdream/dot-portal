<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\Driver\DriverMedicalCardObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([DriverMedicalCardObserver::class])]
class DriverMedicalCard extends Model
{
    
    protected $table = 'driver_medical_card';
    
    protected $fillable = [
        'driver_id',
        'examiner_name',
        'national_registry',
        'issue_date',
        'expiration_date',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}
