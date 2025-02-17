<?php

namespace App\Models;

use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    use Metaable;
    protected $table = 'drivers';
    
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'phone',
        'email',
        'dob',
        'ssn',
        'hire_date',
        'driver_type_id',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(DriverHistory::class, 'item_id');
    }

    public function documents()
    {
        return $this->hasMany(DriverDocument::class);
    }

    public function address()
    {
        return $this->hasOne(DriverAddress::class, 'item_id');
    }

    public function license()
    {
        return $this->hasOne(DriverLicense::class);
    }

    public function medicalCard()
    {
        return $this->hasOne(DriverMedicalCard::class);
    }

    // Driver type
    public function driverType()
    {
        return $this->belongsTo(RefDriverType::class, 'driver_type_id');
    }

}
