<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentCard extends Model
{
    
    protected $table = 'user_payment_cards';
    
    protected $fillable = [
        'user_id',
        'card_number',
        'card_holder_name',
        'expiry_date',
        'payment_method_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
