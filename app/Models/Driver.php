<?php

namespace App\Models;

use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    use Metaable;
    protected $table = 'drivers';
    
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(DriverHistory::class);
    }

    public function documents()
    {
        return $this->hasMany(DriverDocument::class);
    }

    public function address()
    {
        return $this->hasOne(DriverAddress::class);
    }

    public function driverType()
    {
        return $this->belongsTo(RefDriverType::class, 'driver_type_id');
    }

    public function licenseType()
    {
        return $this->belongsTo(RefDriverLicenseType::class, 'license_type_id');
    }

    public function licenseEndorsement()
    {
        return $this->belongsTo(RefDriverLicenseEndorsement::class, 'license_endrs_id');
    }

}
