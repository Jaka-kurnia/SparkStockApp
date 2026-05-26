<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'code',
        'complaint_name',
        'price',
        'is_service',
        'description',
    ];
}
