<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    
    protected $table = 'subscriptions';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'price_per_driver',
        'drivers_amount_from',
        'drivers_amount_to',
        'discount',
        'duration',
        'short_description',
        'description',
    ];

    public function points()
    {
        return $this->hasMany(SubscriptionPoint::class, 'subscription_id');
    }

}
