<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
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
// end route untuk sparepart

// route untuk customer
Route::get('customer/export-excel', [CustomerController::class, 'exportExcel'])->name('customer.export.Excel');
Route::get('customer/export-pdf', [CustomerController::class, 'exportPdf'])->name('customer.export.Pdf');

Route::resource('customer', CustomerController::class);
// end route untuk customer

// route service
Route::get('service/export-pdf', [ServiceController::class, 'exportPdf'])->name('service.exportPdf');
Route::get('service/export-excel', [ServiceController::class, 'exportExcel'])->name('service.exportExcel');
Route::resource('service', ServiceController::class);
// end route service