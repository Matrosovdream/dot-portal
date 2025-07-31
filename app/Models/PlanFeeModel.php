<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeeModel extends Model
{

    protected $table = 'plan_fees';

    protected $fillable = [
        'name',
        'price',
        'discount',
        'short_description',
        'description',
        'user_role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
