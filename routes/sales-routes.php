<?php


use App\Http\Controllers\Sale\AccountSettingController;
use App\Http\Controllers\Sale\DashboardController;
use App\Http\Controllers\Sale\DeveloperController;
use App\Http\Controllers\Sale\FollowupController;
use App\Http\Controllers\Sale\LeadController;
use App\Http\Controllers\Sale\MarketingOrderController;
use App\Http\Controllers\Sale\OrderController;
use App\Http\Controllers\Sale\PaymentController;
use App\Http\Controllers\Sale\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sale'])->prefix('sale')->name('sale.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::get('/all-leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/add-lead', [LeadController::class, 'create'])->name('leads.create');
    Route::get('/update-lead', [LeadController::class, 'edit'])->name('leads.edit');
    Route::get('/lead-followup', [FollowupController::class, 'index'])->name('leads.followup');

    // Losted Leads
    Route::get('/losted-leads', [LeadController::class, 'lostedLeads'])->name('losted-leads');

    // Orders
    Route::get('/all-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/add-order', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/update-order', [OrderController::class, 'edit'])->name('orders.edit');
    Route::get('/order-followup', [FollowupController::class, 'index'])->name('orders.followup');

    // Marketing Orders
    Route::get('/add-marketing-orders', [MarketingOrderController::class, 'index'])->name('marketing-orders');


    // Project
    Route::get('/all-projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/project/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('/project/show', [ProjectController::class, 'show'])->name('projects.show');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');

    // Developer
    Route::get('/add-developer', [DeveloperController::class, 'index'])->name('developer');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
});
