<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_payments';

    protected $fillable = [
        'order_id',
        'item_name',
        'item_description',
        'entity',
        'entity_id',
        'quantity',
        'unit_price',
        'total_price',  
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}