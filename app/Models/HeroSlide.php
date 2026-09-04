<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'tag',
        'title1',
        'title2',
        'title3',
        'description',
        'image',
        'object_pos',
        'fit_mode',
        'btn_text',
        'btn_link',
        'sub_text',
        'sub_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
