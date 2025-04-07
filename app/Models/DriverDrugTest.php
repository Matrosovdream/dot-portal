<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDrugTest extends Model
{
    
    protected $table = 'driver_drug_test';

    protected $fillable = [
        'driver_id',
        'test_date',
        'test_type',
        'result',
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
