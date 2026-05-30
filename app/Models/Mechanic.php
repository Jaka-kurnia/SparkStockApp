<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $fillable = [
        'user_id',
        'name_mechanic',
        'phone',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
