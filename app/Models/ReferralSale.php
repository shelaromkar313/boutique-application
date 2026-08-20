<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'associate_id',
        'order_no',
        'product_name',
        'sale_amount',
        'commission_rate',
        'commission_earned',
        'customer_name',
        'status',
    ];

    protected $casts = [
        'sale_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_earned' => 'decimal:2',
    ];

    public function associate()
    {
        return $this->belongsTo(User::class, 'associate_id');
    }
}
