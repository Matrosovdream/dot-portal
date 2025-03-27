<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileTag extends Model
{
    
    protected $table = 'file_tags';
    
    protected $fillable = ['file_id', 'name'];

    public $timestamps = false;
    
    public function file()
    {
        return $this->belongsTo(File::class);
    }

}
