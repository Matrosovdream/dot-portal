<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefOrderStatus extends Model
{
    protected $table = 'ref_order_statuses';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

}