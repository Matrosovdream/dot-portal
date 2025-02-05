<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefCountryStates extends Model
{
    
    protected $table = 'ref_country_states';
    
    protected $fillable = [
        'name',
        'code',
        'country_id',
    ];

}
