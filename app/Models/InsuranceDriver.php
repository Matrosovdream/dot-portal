<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceDriver extends Model
{
    
    protected $table = 'insurances_driver';

    protected $fillable = [
        'name',
        'number',
        'start_date',
        'end_date',
        'file_id',
        'company_id',
        'user_id',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
