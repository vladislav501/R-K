<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = ['pickup_point_id', 'status'];

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id');
    }

    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    public function isFullyReceived()
    {
        return $this->items->every(fn($item) => $item->is_fully_received);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'sent_to_store' => 'Отправлена в магазин',
            'received' => 'Получена полностью',
            'partially_received' => 'Получена частично',
            default => ucfirst($this->status),
        };
    }
}