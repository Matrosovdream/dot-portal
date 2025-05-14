<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentCardMeta extends Model
{
    
    protected $table = "user_payment_card_meta";

    protected $fillable = [
        'card_id',
        'key',
        'value',
    ];

}
