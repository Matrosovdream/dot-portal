<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefDriverLicenseType extends Model
{
    
    protected $table = 'ref_driver_license_type';
    
    protected $fillable = [
        'title',
        'slug',
        'is_published',
    ];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_license_type', 'driver_id', 'license_type_id');
    }

}
