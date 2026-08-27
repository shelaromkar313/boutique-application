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
        'sales_price',
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
            'sales_price' => 'decimal:2',
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
     * Helper to get effective sales partner price
     */
    public function getEffectiveSalesPriceAttribute(): float
    {
        return (float) ($this->sales_price && $this->sales_price > 0 ? $this->sales_price : ($this->price * 1.05));
    }

    /**
     * Customer reviews relationship
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_est_id', 'est_id');
    }

    /**
     * Recalculate average star rating and review count from approved reviews
     */
    public function updateRatingStats(): void
    {
        $approvedReviews = Review::where('product_est_id', $this->est_id)
            ->where('is_approved', true)
            ->get();

        if ($approvedReviews->count() > 0) {
            $this->rating = round($approvedReviews->avg('rating'), 1);
            $this->review_count = $approvedReviews->count();
        } else {
            $this->rating = 5.0;
            $this->review_count = 0;
        }
        $this->save();
    }

    /**
     * Calculate associate margin profit
     */
    public function getAssociateMarginAttribute(): float
    {
        return max(0, $this->effective_sales_price - (float) $this->price);
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
            'sales_price' => 'salesPrice',
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
