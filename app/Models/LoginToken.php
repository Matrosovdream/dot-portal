<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginToken extends Model
{
    protected $fillable = [
        'user_id', 
        'token', 
        'expires_at',
        'max_uses'
    ];
    protected $dates = ['expires_at'];

    // Is valid token?
    public function isValid( $token=null ): bool
    {
        if ($token) {
            $tokenSet = $this->where('token', $token)->first();
            if (!$tokenSet) {
                return false;
            }
        }

        // Check if token is expired or has no uses left
        if ($tokenSet->expires_at < now() || $tokenSet->max_uses <= 0) {
            return false;
        }

        // Token is valid
        return true;
    }

    // Find user by token
    public static function findByToken( $token ): ?self
    {
        return self::where('token', $token)->first();
    }

    // use token
    // Decrease max_uses and delete token if no uses left
    public function useToken()
    {
        $this->max_uses--;

        if ($this->max_uses <= 0) {
            $this->delete();
        } else {
            $this->save();
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
