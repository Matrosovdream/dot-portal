<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionCustomRequest extends Model
{
    
    protected $table = 'subscription_custom_requests';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'user_subscription_id',
        'request_details',
        'status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function userSubscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

}
