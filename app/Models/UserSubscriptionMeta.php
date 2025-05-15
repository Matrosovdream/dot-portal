<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscriptionMeta extends Model
{
    
    protected $table = "user_subscription_meta";

    protected $fillable = [
        'subscription_id',
        'key',
        'value',
    ];

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

}
