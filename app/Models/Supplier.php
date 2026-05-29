<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

   public function stokTransactions() : HasMany
    {
        return $this->hasMany(StokTransaction::class, 'supplier_id', 'id');
    }
}
