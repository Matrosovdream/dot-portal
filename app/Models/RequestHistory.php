<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestHistory extends Model
{
    
    protected $table = 'request_history';
    
    protected $fillable = [
        'request_id', 
        'status_id', 
        'comment', 
        'user_id'
    ];
    
    public function request()
    {
        return $this->belongsTo(Request::class);
    }
    
    public function status()
    {
        return $this->belongsTo(RefRequestStatus::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
