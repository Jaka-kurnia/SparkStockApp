<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'service_order_id',
        'user_id',
        'payment_proof',
        'amount',
        'method',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
