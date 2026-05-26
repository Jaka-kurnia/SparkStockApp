<?php

use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// route untuk supplier
Route::resource('supplier', SupplierController::class);

// route untuk sparepart
Route::resource('sparepart', SparepartController::class);