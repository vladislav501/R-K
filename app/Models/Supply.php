<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'status'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    public function isFullyReceived()
    {
        return $this->items->every(fn($item) => $item->is_fully_received);
    }
}