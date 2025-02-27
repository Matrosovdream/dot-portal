<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    
    protected $table = 'user_address';

    protected $fillable = [
        'user_id',
        'address1',
        'address2',
        'city',
        'state_id',
        'zip',
    ];

    public function state() {

        return $this->belongsTo( RefCountryStates::class);

    }



}
