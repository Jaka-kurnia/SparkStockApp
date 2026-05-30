<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderService extends Model
{
    protected $fillable = [
        'service_order_id',
        'service_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
