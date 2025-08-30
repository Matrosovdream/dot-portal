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
        'user_id',
        'search_index',
    ];

    protected static function booted(): void
    {
        static::creating(function ($file) {

            // Search index
            $file->search_index = self::prepareSearchIndex( $file);
        });     

        static::updating(function ($file) {

            // Search index
            $file->search_index = self::prepareSearchIndex( $file);
        });
    }

    public function toSearchableArray()
    {
        return [
            'filename' => $this->filename,
            'description' => $this->description,
            'tags' => $this->tags->pluck('name')->toArray(),
        ];
    }

    protected static function prepareSearchIndex( $file )
    {

        $fields = [
            $file->filename ?? '',
            $file->description ?? '',
            implode(' ', $file->tags->pluck('name')->toArray()) ?? '',
        ];

        // Remove empty fields from the array
        foreach ($fields as $key => $value) {
            if (empty($value) || $value == "") {
                unset($fields[$key]);
            }
        }

        $searchIndex = implode(' | ', $fields);
        return $searchIndex;
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
