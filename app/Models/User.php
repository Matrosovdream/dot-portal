<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'birthday',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

 
    // Get roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // Get company
    public function company()
    {
        return $this->hasOne(UserCompany::class);
    }

    // User address
    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    // Get subscriptions
    public function subscriptions()
    {
        return $this->hasOne(Subscription::class);
    }

    // Get user payment cards
    public function paymentCards()
    {
        return $this->hasMany(UserPaymentCard::class);
    }

    // Get user payment history
    public function paymentHistory()
    {
        return $this->hasMany(UserPaymentHistory::class);
    }

    // Check if user has role admin
    public function isAdmin()
    {
        return $this->roles()->where('slug', 'admin')->exists();
    }

    // Check if user has role manager
    public function isManager()
    {
        return $this->roles()->where('slug', 'manager')->exists();
    }

    // Check if user has role user
    public function isUser()
    {
        return $this->roles()->where('slug', 'company')->exists();
    }

    // Get the user role
    public function getRole()
    {
        return $this->roles()->first();
    }

    // Check if user has role
    public function hasRole($roles)
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    public function setRole($role_slug)
    {
        $role = Role::where('slug', $role_slug)->first();
        if (!$role) { return false; }
        return $this->roles()->sync($role->id);
    }



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
