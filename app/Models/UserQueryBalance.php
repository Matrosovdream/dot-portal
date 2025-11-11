<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQueryBalance extends Model {

    protected $table = 'user_query_balance';   

    protected $fillable = [
        'user_id',
        'user_company_id',
        'type',
        'amount',
    ];

    public function history()
    {
        return $this->hasMany(UserQueryBalanceHistory::class, 'query_balance_id');
    }


}