<?php

use App\Http\Controllers\Admin\AccountSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\FollowupController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MarketingOrderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SalesPersonController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\Admin\StatusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sales Person
    Route::get('/add-sales-person', [SalesPersonController::class, 'index'])->name('sales-person');

    // Developer
    Route::get('/add-developer', [DeveloperController::class, 'index'])->name('developer');

    // Sources
    Route::get('/add-sources', [SourceController::class, 'index'])->name('sources');

    // Services
    Route::get('/add-services', [ServiceController::class, 'index'])->name('services');
    
    // Status
    Route::get('/add-status', [StatusController::class, 'index'])->name('status');

    // Leads
    Route::get('/add-leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/lead-followup', [FollowupController::class, 'index'])->name('leads.followup');
    
    // Losted Leads
    Route::get('/losted-leads', [LeadController::class, 'lostedLeads'])->name('losted-leads');

    // Orders
    Route::get('/add-orders', [OrderController::class, 'index'])->name('orders');

    // Project
    Route::get('/all-projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/project/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('/project/show', [ProjectController::class, 'show'])->name('projects.show');
    
    // Marketing Orders
    Route::get('/add-marketing-orders', [MarketingOrderController::class, 'index'])->name('marketing-orders');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
});
