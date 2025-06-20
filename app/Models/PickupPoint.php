<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    protected $fillable = ['name', 'address', 'hours', 'user_id'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_pickup_point', 'pickup_point_id', 'product_id')->withPivot('quantity');
    }

    public function getCurrentStock(int $productId, int $colorId, int $sizeId): int
    {
        $receivedQuantity = SupplyItem::whereHas('supply', function ($query) {
            $query->where('pickup_point_id', $this->id)
                  ->whereIn('status', ['received', 'partially_received']);
        })
        ->where('product_id', $productId)
        ->where('color_id', $colorId)
        ->where('size_id', $sizeId)
        ->sum('received_quantity');

        $orderedQuantity = OrderItem::whereHas('order', function ($query) {
            $query->where('pickup_point_id', $this->id)
                  ->where('status', 'confirmed');
        })
        ->where('product_id', $productId)
        ->where('color_id', $colorId)
        ->where('size_id', $sizeId)
        ->sum('quantity');

        return max(0, $receivedQuantity - $orderedQuantity);
    }
}