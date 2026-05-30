<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MidtransTransaction extends Model
{
    protected $fillable = [
        'service_order_id',
        'order_id',
        'snap_token',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'payment_code',
        'expiry_time',
        'settlement_time',
        'response_payload',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class); 
    }
}
