<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
        return $this->belongsToMany(Color::class, 'product_color');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function colorSizes()
    {
        return $this->hasMany(ProductColorSize::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'product_store')->withPivot('quantity');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
    }
}

?>