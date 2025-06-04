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
}