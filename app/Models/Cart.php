<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'size_id', 'color_id', 'order_amount',
        'order_id', 'delivery_method', 'delivery_address', 'pickup_point_id', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}