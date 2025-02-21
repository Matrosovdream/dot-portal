<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Metaable;

class Service extends Model
{

    use Metaable;
    
    protected $table = 'services';
    
    protected $fillable = [
        'name', 
        'slug',
        'description', 
        'price', 
        'status_id',
        'group_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(RefServiceGroup::class, 'group_id');
    }

}
