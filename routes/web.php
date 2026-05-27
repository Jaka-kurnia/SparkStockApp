<?php

use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// route untuk supplier
Route::get('supplier/export-excel', [SupplierController::class, 'exportExcel'])->name('supplier.exportExcel');
Route::get('supplier/export-pdf', [SupplierController::class, 'exportPdf'])->name('supplier.exportPdf');
Route::resource('supplier', SupplierController::class);
// end route untuk supplier

// route untuk sparepart
Route::get('sparepart/export-excel', [SparepartController::class, 'exportExcel'])->name('sparepart.exportExcel');
Route::get('sparepart/export-pdf', [SparepartController::class, 'exportPdf'])->name('sparepart.exportPdf');
Route::resource('sparepart', SparepartController::class);