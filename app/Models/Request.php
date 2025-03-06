<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Metaable;

class Request extends Model
{

    use Metaable;

    protected $table = 'requests';

    protected $fillable = [
        'user_id', 
        'status_id',
        'service_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function status()
    {
        return $this->belongsTo(RefRequestStatus::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
    
    public function meta()
    {
        return $this->hasMany(RequestMeta::class);
    }
    
    public function history()
    {
        return $this->hasMany(RequestHistory::class);
    }

    public function fieldValues()
    {
        return $this->hasMany(RequestFieldValue::class);
    }

}
