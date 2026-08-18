<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'url',
        'category',
        'section',
        'alt_text',
        'width',
        'height',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'width'     => 'integer',
        'height'    => 'integer',
        'sort_order'=> 'integer',
    ];

    /**
     * Scope to active images only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category.
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by section.
     */
    public function scopeOfSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Get image URL by slug (static helper).
     */
    public static function url(string $slug, string $fallback = ''): string
    {
        $img = static::where('slug', $slug)->where('is_active', true)->first();
        return $img ? $img->url : $fallback;
    }
}
