<?php

namespace App\Models;


use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Observers\Driver\DriverObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([DriverObserver::class])]
class Driver extends Model
{

    use Metaable;
    use Searchable;
    protected $table = 'drivers';
    
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'phone',
        'email',
        'dob',
        'ssn',
        'hire_date',
        'driver_type_id',
        'user_id',
        'company_id',
        'company_user_id',
        'profile_photo_id',
        'status_id', // 1 - active, 2 - inactive, 3 - terminated
        'is_finished',
    ];

    protected static function booted(): void
    {
        static::creating(function ($driver) {
            self::updateCompanyUser( $driver );
        });     

        static::updating(function ($driver) {
            self::updateCompanyUser( $driver );
        });
    }

    public function toSearchableArray()
    {
        return [
            'firstname' => $this->user->firstname ?? '',
            'middlename' => $this->user->middlename ?? '',
            'lastname' => $this->user->lastname ?? '',
            'phone' => $this->user->phone ?? '',
            'email' => $this->user->email ?? '',
            'ssn' => $this->ssn ?? '',
            'license_number' => $this->license->license_number ?? '',
            'medical_national_registry' => $this->medicalCard->national_registry ?? '',
        ];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userCompany()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function history()
    {
        return $this->hasMany(DriverHistory::class, 'item_id');
    }

    public function documents()
    {
        return $this->hasMany(DriverDocument::class);
    }

    public function address()
    {
        return $this->hasOne(DriverAddress::class, 'item_id');
    }

    public function license()
    {
        return $this->hasOne(DriverLicense::class);
    }

    public function cdlLicense()
    {
        return $this->hasOne(DriverCdlLicense::class);
    }

    public function medicalCard()
    {
        return $this->hasOne(DriverMedicalCard::class);
    }

    public function driverType()
    {
        return $this->belongsTo(RefDriverType::class, 'driver_type_id');
    }

    public function profilePhoto()
    {
        return $this->belongsTo(File::class, 'profile_photo_id');
    }

    public function drugTest()
    {
        return $this->hasOne(DriverDrugTest::class);
    }

    public function mvr()
    {
        return $this->hasOne(DriverMvr::class);
    }

    public static function updateCompanyUser( $driver ) {

        if ($driver->company_id) {
            $userCompany = UserCompany::find($driver->company_id);
            if ($userCompany) {
                $driver->company_user_id = $userCompany->user_id;
            }
        }

    }

}
