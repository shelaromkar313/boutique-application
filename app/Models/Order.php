<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no', 'user_id', 'full_name', 'email', 'phone',
        'address', 'city', 'state', 'pincode', 'subtotal',
        'shipping', 'discount', 'total', 'currency',
        'payment_id', 'razorpay_order_id', 'signature',
        'status', 'items', 'note',
    ];

    protected $casts = [
        'items' => 'json',
        'status' => 'string',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
