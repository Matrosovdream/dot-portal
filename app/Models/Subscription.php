<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    
    protected $table = 'subscriptions';

    protected $fillable = [
        'name',
        'price',
        'discount',
        'duration',
        'short_description',
        'description',
        'drivers_amount',
    ];

    public function points()
    {
        return $this->hasMany(SubscriptionPoint::class, 'subscription_id');
    }

}
