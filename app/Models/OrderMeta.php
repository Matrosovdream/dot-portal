<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMeta extends Model
{
    
    protected $table = 'order_meta';
    
    protected $fillable = [
        'item_id',
        'key',
        'value',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'item_id');
    }

}
