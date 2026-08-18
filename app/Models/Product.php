<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'est_id',
        'name',
        'category',
        'main_category',
        'sub_category',
        'fabric',
        'occasion',
        'price',
        'old_price',
        'discount',
        'rating',
        'review_count',
        'is_new_arrival',
        'is_best_seller',
        'is_trending',
        'is_featured',
        'in_stock',
        'sku',
        'colors',
        'sizes',
        'description',
        'details',
        'care',
        'images',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'discount' => 'integer',
            'rating' => 'decimal:2',
            'review_count' => 'integer',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_trending' => 'boolean',
            'is_featured' => 'boolean',
            'in_stock' => 'boolean',
            'colors' => 'array',
            'sizes' => 'array',
            'details' => 'array',
            'images' => 'array',
        ];
    }

    /**
     * Serialize using the camelCase keys expected by the frontend.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $aliases = [
            'main_category' => 'mainCategory',
            'sub_category' => 'subCategory',
            'old_price' => 'oldPrice',
            'review_count' => 'reviewCount',
            'is_new_arrival' => 'isNewArrival',
            'is_best_seller' => 'isBestSeller',
            'is_trending' => 'isTrending',
            'is_featured' => 'isFeatured',
            'in_stock' => 'inStock',
        ];

        $attributes = parent::attributesToArray();
        $array = [];

        foreach ($attributes as $key => $value) {
            $array[$aliases[$key] ?? $key] = $value;
        }

        return $array;
    }
}
