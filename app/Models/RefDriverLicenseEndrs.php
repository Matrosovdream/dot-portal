<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefDriverLicenseEndrs extends Model
{
    
    protected $table = 'ref_driver_license_endrs';
    
    protected $fillable = [
        'title',
        'slug',
        'is_published',
    ];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_license_endrs', 'driver_id', 'license_endrs_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('is_published', false);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

}
