<?php

namespace App\Models;

use App\Traits\Metaable;
use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{

    use Metaable;

    protected $table = "user_tasks";
    
    protected $fillable = [
        'unique_code',
        'user_id',
        'assigned_to',
        'title',
        'description',
        'category',
        'subcategory',
        'status',
        'due_date',
        'completed_at',
        'priority',
        'link',
        'entity',
        'tab',
        'entity_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAssigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function meta()
    {
        return $this->hasMany(UserTaskMeta::class, 'task_id');
    }

}
