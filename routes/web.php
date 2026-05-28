<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/switch/{role}', [AuthController::class, 'quickSwitch'])->name('auth.switch');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Route untuk supplier (Requires manage-suppliers permission)
    Route::middleware(['permission:manage-suppliers'])->group(function () {
        Route::get('supplier/export-excel', [SupplierController::class, 'exportExcel'])->name('supplier.exportExcel');
        Route::get('supplier/export-pdf', [SupplierController::class, 'exportPdf'])->name('supplier.exportPdf');
        Route::resource('supplier', SupplierController::class);
    });

    // Route untuk sparepart (Requires manage-spareparts permission)
    Route::middleware(['permission:manage-spareparts'])->group(function () {
        Route::get('sparepart/export-excel', [SparepartController::class, 'exportExcel'])->name('sparepart.exportExcel');
        Route::get('sparepart/export-pdf', [SparepartController::class, 'exportPdf'])->name('sparepart.exportPdf');
        Route::resource('sparepart', SparepartController::class);
    });

    // Route untuk customer (Requires manage-customers permission)
    Route::middleware(['permission:manage-customers'])->group(function () {
        Route::get('customer/export-excel', [CustomerController::class, 'exportExcel'])->name('customer.export.Excel');
        Route::get('customer/export-pdf', [CustomerController::class, 'exportPdf'])->name('customer.export.Pdf');
        Route::resource('customer', CustomerController::class);
    });

    // Route service (Requires manage-services permission)
    Route::middleware(['permission:manage-services'])->group(function () {
        Route::get('service/export-pdf', [ServiceController::class, 'exportPdf'])->name('service.exportPdf');
        Route::get('service/export-excel', [ServiceController::class, 'exportExcel'])->name('service.exportExcel');
        Route::resource('service', ServiceController::class);
    });

    // Route vehicle (Requires manage-vehicles permission)
    Route::middleware(['permission:manage-vehicles'])->group(function () {
        Route::get('vehicle/export-pdf', [VehicleController::class, 'exportPdf'])->name('vehicle.exportPdf');
        Route::get('vehicle/export-excel', [VehicleController::class, 'exportExcel'])->name('vehicle.exportExcel');
        Route::resource('vehicle', VehicleController::class);
    });
    
    Route::middleware(['permission:manage-mechanic'])->group(function () {
        Route::get('mechanic/export-pdf', [MechanicController::class, 'exportPdf'])->name('mechanic.exportPdf');
        Route::get('mechanic/export-excel', [MechanicController::class, 'exportExcel'])->name('mechanic.exportExcel');
        Route::resource('mechanic', MechanicController::class);
    });

    // Owner only: Role & Permission Management
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::post('/permissions/toggle', [RolePermissionController::class, 'toggle'])->name('role-permissions.toggle');
    });
});