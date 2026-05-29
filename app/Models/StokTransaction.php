<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokTransaction extends Model
{
    protected $fillable = [
        'sparepart_id',
        'supplier_id',
        'user_id',
        'type',
        'qty',
        'price_per_unit',
        'total_amount',
        'note'
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
