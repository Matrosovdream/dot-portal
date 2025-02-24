<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    
    protected $table = 'user_subscription';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'price',
        'discount',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(UserSubscriptionPayment::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

}
