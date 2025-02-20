<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefFormFields extends Model
{
    
    protected $table = 'ref_form_fields';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function groups()
    {
        return $this->hasMany(ServiceGroups::class);
    }

}
