<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Metaable;

class Request extends Model
{

    use Metaable;

    protected $table = 'requests';

    protected $fillable = [
        'name', 
        'user_id', 
        'status_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function status()
    {
        return $this->belongsTo(RefRequestStatus::class);
    }
    
    public function meta()
    {
        return $this->hasMany(RequestMeta::class);
    }
    
    public function history()
    {
        return $this->hasMany(RequestHistory::class);
    }


}
