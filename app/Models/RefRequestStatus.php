<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefRequestStatus extends Model
{
    
    protected $table = 'ref_request_statuses';

    protected $fillable = [
        'name', 
        'slug',
        'color', 
        'published'
    ];
    
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function scopeActive($query)
    {
        return $query->where('published', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('published', false);
    }

}
