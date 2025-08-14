<?php

namespace App\Models;

use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{

    use Metaable;
    protected $table = 'user_subscription';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'price',
        'price_per_driver',
        'drivers_number',
        'discount',
        'payment_card_id',
        'start_date',
        'next_date',
        'end_date',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            // Default calculation
            $model->price = $model->price_per_driver * $model->drivers_number;

            // Optional: Apply discount if present
            if (!empty($model->discount)) {
                $model->price -= $model->price * ($model->discount / 100);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(UserSubscriptionPayment::class);
    }

    public function meta()
    {
        return $this->hasMany(UserSubscriptionMeta::class, 'subscription_id');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

}
