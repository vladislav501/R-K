<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total', 'status', 'delivery_method', 'delivery_address', 'pickup_point_id', 'order_date',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'assembling' => 'Сборка',
            'assembled' => 'Собран',
            'ready_for_pickup' => 'Готов к выдаче',
            'handed_to_courier' => 'Передан курьеру',
            'completed' => 'Завершён',
            'cancelled' => 'Отменён',
            default => 'Неизвестно',
        };
    }
}