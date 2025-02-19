<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceFormField extends Model
{

    protected $table = 'ref_form_fields';

    protected $fillable = [
        'title',
        'slug',
        'entity',
        'type',
        'section',
        'placeholder',
        'tooltip',
        'description',
        'default_value',
        'reference_code',
        'icon',
        'classes'
    ];

}
