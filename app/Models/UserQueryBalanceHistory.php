<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQueryBalanceHistory extends Model {

    protected $table = 'user_query_balance_history';   

    protected $fillable = [
        'user_id',
        'user_company_id',
        'amount',
        'type',
        'initiator',
        'initiator_id',
        'notes',
    ];


}