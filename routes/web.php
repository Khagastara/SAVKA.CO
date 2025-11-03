<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ReportController;

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

    // Owner only
    Route::get('/account/staff', [AccountController::class, 'showStaff']);
    Route::post('/account/staff', [AccountController::class, 'createStaff']);
    Route::put('/account/staff/{id}', [AccountController::class, 'updateStaff']);

    // View all materials (Owner & Production Staff)
    Route::get('/materials', [SupplyController::class, 'index']);

    // Owner only routes
    Route::post('/materials', [SupplyController::class, 'storeMaterial']);
    Route::put('/materials/{id}', [SupplyController::class, 'updateMaterial']);
    Route::post('/procurements', [SupplyController::class, 'storeProcurement']);

    Route::get('/productions', [ProductionController::class, 'index']);
    Route::post('/productions', [ProductionController::class, 'store']);
    Route::put('/productions/{id}/status', [ProductionController::class, 'updateStatus']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);

    Route::get('/shipments', [ShipmentController::class, 'index']);
    Route::post('/shipments', [ShipmentController::class, 'store']);
    Route::put('/shipments/{id}', [ShipmentController::class, 'update']);
    Route::patch('/shipments/{id}/delivered', [ShipmentController::class, 'markAsDelivered']);

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/download/csv/{month}/{year}', [ReportController::class, 'downloadMonthlyReportCSV']);
    Route::get('/reports/download/pdf/{month}/{year}', [ReportController::class, 'downloadMonthlyReportPDF']);
});
