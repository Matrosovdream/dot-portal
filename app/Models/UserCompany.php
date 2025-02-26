<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    
    protected $table = 'user_company';
    
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'dot_number',
        'mc_number',
        'business_address',
        'mailing_address'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get company addresses
    public function addresses()
    {
        return $this->hasMany(UserCompanyAddress::class, 'item_id');
    }

    // Get business address
    public function businessAddress()
    {
        return $this->addresses()->where('type', 'business');
    }

    // Get mailing address
    public function mailingAddress()
    {
        return $this->addresses()->where('type', 'mailing');
    }

}
