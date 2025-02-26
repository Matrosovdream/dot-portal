<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCompanyAddress extends Model
{

    protected $table = 'user_company_address';

    protected $fillable = [
        'item_id',
        'type',
        'address1',
        'address2',
        'city',
        'state',
        'zip'
    ];

    public function company()
    {
        return $this->belongsTo(UserCompany::class, 'item_id');
    }

    public function state()
    {
        return $this->belongsTo(RefCountryStates::class, 'state_id');
    }

}
