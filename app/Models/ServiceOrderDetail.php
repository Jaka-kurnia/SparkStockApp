<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderDetail extends Model
{
    protected $fillable = [
        'service_order_id',
        'sparepart_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}
