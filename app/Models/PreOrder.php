<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;

    protected $fillable = ['userId', 'totalSum', 'status', 'productId'];

    public function getProducts()
    {
        $productId = explode(',', $this->productId); 
        return Product::whereIn('id', $productId)->get();
    }
}
