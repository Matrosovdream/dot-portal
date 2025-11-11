<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefQueryPrice extends Model
{
    
    protected $table = 'ref_query_prices';
    
    protected $fillable = [
        'type',
        'price_per_query',
        'rules',
    ];

    public $timestamps = true;

    public function getRulesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRulesAttribute($value)
    {
        $this->attributes['rules'] = json_encode($value);   
    }

}