<?php


use App\Http\Controllers\Sale\AccountSettingController;
use App\Http\Controllers\Sale\DashboardController;
use App\Http\Controllers\Sale\FollowupController;
use App\Http\Controllers\Sale\LeadController;
use App\Http\Controllers\Sale\MarketingOrderController;
use App\Http\Controllers\Sale\OrderController;
use App\Http\Controllers\Sale\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sale'])->prefix('sale')->name('sale.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::get('/add-leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/lead-followup', [FollowupController::class, 'index'])->name('leads.followup');

    // Orders
    Route::get('/add-orders', [OrderController::class, 'index'])->name('orders');

    // Marketing Orders
    Route::get('/add-marketing-orders', [MarketingOrderController::class, 'index'])->name('marketing-orders');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
});
