<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\Driver\DriverMvrObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([DriverMvrObserver::class])]
class DriverMvr extends Model
{
    
    protected $table = 'driver_mvr';

    protected $fillable = [
        'driver_id',
        'mvr_number',
        'mvr_date',
        'file_id',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

}
