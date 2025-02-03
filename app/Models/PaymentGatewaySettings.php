<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySettings extends Model
{
    protected $fillable = [
        'gateway_id',
        'key',
        'value',
    ];

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

}
