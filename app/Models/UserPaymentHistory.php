<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentHistory extends Model
{
    
    protected $table = 'user_company';
    
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'subscription_id',
        'type',
        'amount',
        'payment_date',
        'transaction_id',
        'status',
        'notes'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class);
    }

}
