<?php

namespace App\Models;


use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;



class Order extends Model
{

    use Metaable;

    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'order_number',
        'amount',
        'discount_amount',
        'status_id',
        'payment_method_id',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function ($order) {
            // Order number
            $order->order_number = self::generateOrderNumber( $order );
        });     
    }

    public function meta()
    {
        return $this->hasMany(OrderMeta::class, 'item_id');
    }

    public function status()
    {
        return $this->belongsTo(RefOrderStatus::class, 'status_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(RefPaymentMethod::class, 'payment_method_id');
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    protected static function generateOrderNumber( $order )
    {
        return strtoupper(uniqid());
    }

}
