<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTaskMeta extends Model
{
    protected $table = "user_task_meta";

    protected $fillable = [
        'task_id',
        'key',
        'value',
    ];

}
