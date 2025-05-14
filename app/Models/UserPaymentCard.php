<?php

namespace App\Models;

use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;

class UserPaymentCard extends Model
{

    use Metaable;
    
    protected $table = 'user_payment_cards';
    
    protected $fillable = [
        'user_id',
        'card_number',
        'card_holder_name',
        'expiry_date',
        'payment_method_id',
        'primary',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meta()
    {
        return $this->hasMany(UserPaymentCardMeta::class, 'card_id');
    }

}
