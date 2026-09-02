<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_order_value',
        'campaign_type',
        'valid_until',
        'usage_count',
        'is_active',
        'is_announced',
        'announcement_text',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_announced' => 'boolean',
        'valid_until'  => 'date',
        'discount_value'  => 'decimal:2',
        'min_order_value' => 'decimal:2',
    ];
}
