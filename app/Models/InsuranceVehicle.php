<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceVehicle extends Model
{
    
    protected $table = 'insurances_vehicle';

    protected $fillable = [
        'name',
        'number',
        'start_date',
        'end_date',
        'file_id',
        'company_id',
        'user_id',
        'search_index'
    ];

    protected static function booted(): void
    {
        // Set fullname on creating and updating
        static::creating(function ($insurance) {
            $insurance->search_index = self::prepareSearchIndex( $insurance );
        });

        static::updating(function ($insurance) {
            $insurance->search_index = self::prepareSearchIndex( $insurance );
        });
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function prepareSearchIndex( $user )
    {

        $fields = [
            $user->name ?? '',
            $user->number ?? '',
        ];

        // Remove empty fields from the array
        foreach ($fields as $key => $value) {
            if (empty($value) || $value == "") {
                unset($fields[$key]);
            }
        }

        $searchIndex = implode(' | ', $fields);
        return $searchIndex;
    }

}
