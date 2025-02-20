<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceGroups extends Model
{
    
    protected $table = 'service_groups';
    protected $fillable = [
        'service_id',
        'group_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function group()
    {
        return $this->belongsTo(RefServiceGroups::class);
    }

}
