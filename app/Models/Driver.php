<?php

namespace App\Models;

use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    use Metaable;
    protected $table = 'drivers';
    
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(DriverHistory::class);
    }
    

}
