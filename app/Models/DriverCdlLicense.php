<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCdlLicense extends Model
{
    
    protected $table = 'driver_cdl_license';

    protected $fillable = [
        'driver_id',
        'license_number',
        'expiration_date',
        'file_id'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

}
