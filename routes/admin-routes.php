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
use App\Http\Controllers\Admin\ProjectTaskController;
use App\Http\Controllers\Admin\SalesPersonController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\StatusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sales Person
    Route::get('/add-sales-person', [SalesPersonController::class, 'index'])->name('sales-person');
    Route::post('/add-sales-person', [SalesPersonController::class, 'store'])->name('sales-person.store');
    Route::put('/add-sales-person/{id}', [SalesPersonController::class, 'edit'])->name('sales-person.update');
    Route::delete('/add-sales-person/{id}', [SalesPersonController::class, 'delete'])->name('sales-person.destroy');

    // Developer
    Route::get('/add-developer', [DeveloperController::class, 'index'])->name('developer');
    Route::post('/add-developer', [DeveloperController::class, 'store'])->name('developer.store');
    Route::put('/add-developer/{id}', [DeveloperController::class, 'edit'])->name('developer.update');
    Route::delete('/add-developer/{id}', [DeveloperController::class, 'delete'])->name('developer.destroy');

    // Sources
    Route::get('/sources',           [SourceController::class, 'index'])->name('sources.index');
    Route::post('/sources',          [SourceController::class, 'store'])->name('sources.store');
    Route::put('/sources/{id}',      [SourceController::class, 'edit'])->name('sources.update');
    Route::delete('/sources/{id}',   [SourceController::class, 'delete'])->name('sources.destroy');

    // Services
    Route::get('/add-services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/add-services', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/add-services/{id}', [ServiceController::class, 'edit'])->name('services.update');
    Route::delete('/add-services/{id}', [ServiceController::class, 'delete'])->name('services.destroy');

    // Campaign
    Route::get('/campaigns',           [CampaignController::class, 'index'])->name('campaign.index');
    Route::post('/campaigns',          [CampaignController::class, 'store'])->name('campaign.store');
    Route::put('/campaigns/{id}',      [CampaignController::class, 'edit'])->name('campaign.update');
    Route::delete('/campaigns/{id}',   [CampaignController::class, 'delete'])->name('campaign.destroy');
    
    // Status
    Route::get('/add-status', [StatusController::class, 'index'])->name('status');
    Route::post('/add-status', [StatusController::class, 'store'])->name('status.store');
    Route::put('/add-status/{id}', [StatusController::class, 'edit'])->name('status.update');
    Route::delete('/add-status/{id}', [StatusController::class, 'delete'])->name('status.destroy');

    // Leads
    Route::get('/all-leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/add-lead', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/add-lead', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/view-lead/{id}', [LeadController::class, 'show'])->name('leads.show');
    Route::patch('/view-lead/{id}/status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
    Route::get('/edit-lead/{id}', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/edit-lead/{id}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/delete-lead/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::get('/lead-followup/{id}', [FollowupController::class, 'index'])->name('leads.followup');
    Route::post('/lead-followup/{id}', [FollowupController::class, 'store'])->name('leads.followup.store');
    
    // Losted Leads
    Route::get('/losted-leads', [LeadController::class, 'lostedLeads'])->name('losted-leads');

    // Orders
    Route::get('/all-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/add-order/{lead_id?}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/all-orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/view-order/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/view-order/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/edit-order/{id}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/edit-order/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/delete-order/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/order-followup/{id}', [FollowupController::class, 'index'])->name('orders.followup');
    Route::post('/order-followup/{id}', [FollowupController::class, 'store'])->name('orders.followup.store');


    // Project
    Route::get('/all-projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/project/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/project/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/project/show/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/project/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/project/update/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::post('/project/quick-update/{id}', [ProjectController::class, 'quickUpdate'])->name('projects.quickUpdate');
    Route::delete('/project/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/project/{project}/tasks', [ProjectTaskController::class, 'index'])->name('projects.tasks');
    Route::post('/project/{project}/tasks', [ProjectTaskController::class, 'store'])->name('projects.tasks.store');
    
    // Marketing Orders
    Route::get('/add-marketing-orders', [MarketingOrderController::class, 'index'])->name('marketing-orders');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create/{order_id}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
    Route::post('/my-account', [\App\Http\Controllers\Auth\LoginController::class, 'adminProfileAndPasswordUpdate'])->name('account-settings.update');
});
