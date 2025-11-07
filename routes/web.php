<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('auth.login');
});
Route::redirect('/', '/login');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Common for all users
    Route::get('/account/profile', [AccountController::class, 'showProfile']);
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::get('/owner/account', function () {
        return view('owner.account');})->name('owner.account');

    Route::get('/staff', [AccountController::class, 'showStaff'])->name('owner.staff.index');
    Route::post('/staff', [AccountController::class, 'createStaff'])->name('owner.staff.store');
    Route::put('/staff/{id}', [AccountController::class, 'updateStaff'])->name('owner.staff.update');
    Route::get('/production/account', function () {
        return view('production.account');})->name('production.account');

    Route::get('/distribution/account', function () {
        return view('distribution.account');})->name('distribution.account');

    // View all materials (Owner & Production Staff)
    Route::get('/materials', [SupplyController::class, 'index'])->name('materials');

    Route::get('/supply/material', function () {
        return view('owner.supply.material');})->name('owner.supply.material');

    Route::get('/supply', function () {
        return view('production.supply.index');})->name('production.supply');

    // Product
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/index', function () {
        return view('owner.product.index');
    })->name('owner.product');

    Route::get('/production/product', function () {
        return view('production.product.index');
    })->name('production.product.index');

    Route::get('/distribution/product', function () {
        return view('distribution.product.index');
    })->name('distribution.product.index');

    //Production
    Route::get('/productions', [ProductionController::class, 'index'])->name('productions');
    Route::post('/productions', [ProductionController::class, 'store'])->name('productions.store');
    Route::put('/productions/{id}/status', [ProductionController::class, 'updateStatus'])->name('productions.updateStatus');

    Route::get('/productions/index', function () {
        return view('owner.product.production');
    })->name('owner.product.production');

    Route::get('/production/productions', function () {
        return view('production.product.production');
    })->name('production.product.production');

    //Shipment
    Route::get('/shipments', [ShipmentController::class, 'index']);
    Route::post('/shipments', [ShipmentController::class, 'store']);
    Route::put('/shipments/{id}', [ShipmentController::class, 'update']);
    Route::patch('/shipments/{id}/delivered', [ShipmentController::class, 'markAsDelivered']);
    Route::get('/shipments/index', function () {
        return view('owner.shipment.index');})->name('owner.shipment.index');

    Route::get('/distribution/shipments', function () {
        return view('distribution.shipment.index');})->name('distribution.shipment.index');

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/download/csv/{month}/{year}', [ReportController::class, 'downloadMonthlyReportCSV']);
    Route::get('/reports/download/pdf/{month}/{year}', [ReportController::class, 'downloadMonthlyReportPDF']);
});
