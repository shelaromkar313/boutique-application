<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'est_id', 'product_name', 'price', 'quantity', 'color', 'size'];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];
}