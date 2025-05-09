<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'userId',
        'products',
        'totalSum',
        'status',
    ];

    public function getProducts()
    {
        $products = json_decode($this->products, true);
        $productIds = array_column($products, 'productId');
        $result = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        return array_map(function ($item) use ($result) {
            return [
                'product' => $result[$item['productId']] ?? null,
                'quantity' => $item['quantity'],
            ];
        }, $products);
    }
}