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
        'status_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
