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
        
        // Remove meta on deletion
        static::deleting(function ($card) {
            $card->meta()->delete();
        });

        // On create: set primary if first, and strip card number to last 4 digits
        static::creating(function ($card) {
            // Auto-set primary if it's the first card for user
            $hasOtherCards = self::where('user_id', $card->user_id)->exists();
            if (!$hasOtherCards) {
                $card->primary = true;
            }

            // Store only last 4 digits of card number
            $card->card_number = substr(preg_replace('/\D/', '', $card->card_number), -4);
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
