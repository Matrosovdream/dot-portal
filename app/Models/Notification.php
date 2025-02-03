<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Metaable;

class Notification extends Model
{
    use Metaable;
    protected $table = 'notifications';
    
    protected $fillable = [
        'title',
        'message',
        'type',
        'status',
        'user_id',
        'user_id_to',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

}
