<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPoint extends Model
{
    
    protected $table = 'subscription_points';

    protected $fillable = [
        'subscription_id',
        'title',
        'description',
        'included',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

}
