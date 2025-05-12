<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brandId', 'sex', 'typeId', 'collectionId', 'categoryId', 'article', 'title',
        'shortTitle', 'description', 'color', 'size', 'price', 'imageId', 'composition',
        'designCountry', 'manufacturenCountry', 'importer', 'availability',
    ];

    protected $casts = [
        'color' => 'array',
        'size' => 'array',
        'availability' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brandId');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'typeId');
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collectionId');
    }

    public function images()
    {
        return $this->hasOne(Image::class, 'productId');
    }
}