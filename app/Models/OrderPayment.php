<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'amount',
        'status',
        'transaction_id',
        'transaction_details',
        'payment_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(RefPaymentMethod::class, 'payment_method_id');
    }

}