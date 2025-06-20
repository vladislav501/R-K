<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'brand_id',
        'category_id',
        'collection_id',
        'clothing_type_id',
        'is_available',
        'image_1',
        'image_2',
        'image_3',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function clothingType()
    {
        return $this->belongsTo(ClothingType::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color_sizes');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_color_sizes');
    }

    public function colorSizes()
    {
        return $this->hasMany(ProductColorSize::class);
    }

    public function pickupPoints()
    {
        return $this->belongsToMany(PickupPoint::class, 'product_pickup_point', 'product_id', 'pickup_point_id')->withPivot('quantity');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }

    public function isInCart()
    {
        if (!Auth::check()) {
            return false;
        }

        return Cart::where('user_id', Auth::id())
            ->where('product_id', $this->id)
            ->exists();
    }
}