<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    protected $table = 'files';

    protected $fillable = [
        'filename',
        'path',
        'type',
        'size',
        'extension',
        'description',
        'disk',
        'visibility',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->hasMany(FileTag::class);
    }

    

}
