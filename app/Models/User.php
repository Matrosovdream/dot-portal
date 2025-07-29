<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{

    use HasFactory;
    use Metaable;

    protected $fillable = [
        'firstname',
        'lastname',
        'fullname',
        'phone',
        'birthday',
        'email',
        'password',
        'is_active',
        'reg_step',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        // Set fullname on creating and updating
        static::creating(function ($user) {
            $user->fullname = trim("{$user->firstname} {$user->lastname}");
        });

        static::updating(function ($user) {
            $user->fullname = trim("{$user->firstname} {$user->lastname}");
        });
    }
 
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

    // Get Saferweb company if user is company
    public function companySaferweb()
    {
        if ($this->isCompany()) {
            return $this->hasOne(CompanySaferweb::class, 'user_id', 'id');
        }
        return null;
    }

    // User address
    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    // User subscription
    public function subscription() 
    {
        return $this->hasOne(UserSubscription::class);
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

    // Get user meta
    public function meta()
    {
        return $this->hasMany(UserMeta::class);
    }

    // Driver
    public function driver()
    {
        return $this->hasOne(Driver::class);
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

    // Chec if user is company
    public function isCompany()
    {
        return $this->roles()->where('slug', 'company')->exists();
    }

    // Check if user is driver
    public function isDriver()
    {
        return $this->roles()->where('slug', 'driver')->exists();
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
