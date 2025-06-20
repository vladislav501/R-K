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
}