<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'productId', 
        'previewImagePath', 
        'imagePath1'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'productId');
    }
}