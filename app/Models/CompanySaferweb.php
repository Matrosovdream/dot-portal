<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySaferweb extends Model
{
    
    protected $table = "company_saferweb";

    protected $fillable = [
        'user_id',
        'company_id',
        'dot_number',
        'mc_number',
        'legal_name',
        'dba_name',
        'entity_type',
        'physical_address',
        'mailing_address',
        'latest_update',
        'api_data',
    ];

    protected $casts = [
        'api_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(UserCompany::class, 'company_id');
    }

}
