<?php

namespace App\Models;

use App\Models\ServiceOrderDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'customer_id',
        'mechanic_id',
        'kode_order',
        'kode_queue',
        'status',
        'keluhan',
        'service_date',
        'total_service',
        'total_part',
        'discount',
        'tax',
        'grand_total',
        'payment_method',
        'payment_status',
        'midtrans_status',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'service_date' => 'date',
        'paid_at' => 'datetime',
        'total_service' => 'decimal:2',
        'total_part' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }


    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(Mechanic::class, 'mechanic_id');
    }

    public function ServiceOrderDetail()
    {
        return $this->hasMany(ServiceOrderDetail::class, 'service_order_id');
    }

    /**
     * Hubungan ke item Jasa Servis yang digunakan (Detail Jasa)
     * Satu order bisa memiliki banyak jasa servis (Many-to-Many via tabel pivot atau HasMany ke tabel detail)
     */
    // public function orderServices(): HasMany
    // {
    //     return $this->hasMany(ServiceOrderService::class, 'service_order_id');
    // }

    /**
     * Hubungan ke item Sparepart yang digunakan (Detail Sparepart)
     */
    // public function orderDetails(): HasMany
    // {
    //     return $this->hasMany(ServiceOrderDetail::class, 'service_order_id');
    // }

    /**
     * Hubungan ke data Riwayat Pembayaran Manual/Kasir
     */
    // public function payments(): HasMany
    // {
    //     return $this->hasMany(Payment::class, 'service_order_id');
    // }

    /**
     * Hubungan ke Transaksi Gateway Midtrans (One-to-One)
     * Karena di rancangan DBML Anda: midtrans_transactions.service_order_id [unique]
     */
    // public function midtransTransaction(): HasOne
    // {
    //     return $this->hasOne(MidtransTransaction::class, 'service_order_id');
    // }
}
