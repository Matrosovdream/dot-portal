<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscriptionPayment extends Model
{
    
    protected $table = 'user_subscription_payments';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'user_subscription_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'transaction_id',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

}
