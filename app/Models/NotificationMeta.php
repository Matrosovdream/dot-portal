<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationMeta extends Model
{
    
    protected $table = 'notification_meta';
    
    protected $fillable = [
        'item_id',
        'key',
        'value',
    ];
    
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'item_id');
    }


}
