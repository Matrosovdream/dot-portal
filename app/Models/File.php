<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class File extends Model
{

    use Searchable;

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

    public function toSearchableArray()
    {
        return [
            'filename' => $this->filename,
            'description' => $this->description,
            'tags' => $this->tags->pluck('name')->toArray(),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->hasMany(FileTag::class);
    }

    

}
