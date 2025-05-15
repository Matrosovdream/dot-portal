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

    protected static function booted()
    {
        
        // Remove meta
        static::deleting(function ($card) {
            $card->meta()->delete();
        });

        // Auto set primary if it's the first card for this user
        static::creating(function ($card) {
            $hasOtherCards = self::where('user_id', $card->user_id)->exists();

            if (!$hasOtherCards) {
                $card->primary = true;
            }
        });

    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meta()
    {
        return $this->hasMany(UserPaymentCardMeta::class, 'card_id');
    }

}
