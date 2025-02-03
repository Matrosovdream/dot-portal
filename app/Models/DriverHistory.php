<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverHistory extends Model
{
    
    protected $table = 'driver_history';
    
    protected $fillable = [
        'item_id',
        'comment',
        'user_id',
    ];
    
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'item_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
