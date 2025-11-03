<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefPaymentMethod extends Model
{
    protected $table = 'ref_payment_methods';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}