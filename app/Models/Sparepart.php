<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'brand',
        'purchase_price',
        'selling_price',
        'stock',
        'location',
    ];

    public function stokTransactions()
    {
        return $this->hasMany(StokTransaction::class, 'sparepart_id', 'id');
    }

    
}
