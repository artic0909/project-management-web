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
use App\Http\Controllers\Sale\MeetingController;
use App\Http\Controllers\Sale\ProjectTaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sale'])->prefix('sale')->name('sale.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // Leads
    Route::get('/all-leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/export-leads', [LeadController::class, 'export'])->name('leads.export');
    Route::post('/import-leads', [LeadController::class, 'import'])->name('leads.import');
    Route::get('/add-lead', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/add-lead', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/view-lead/{id}', [LeadController::class, 'show'])->name('leads.show');
    Route::get('/edit-lead/{id}', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/edit-lead/{id}', [LeadController::class, 'update'])->name('leads.update');
    Route::patch('/update-lead-status/{id}', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
    Route::delete('/delete-lead/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::delete('/delete-leads', [LeadController::class, 'bulkDestroy'])->name('leads.bulk-destroy');
    Route::get('/lead-followup/{id}', [FollowupController::class, 'index'])->name('leads.followup');
    Route::post('/lead-followup/{id}', [FollowupController::class, 'store'])->name('leads.followup.store');
    
    // Lead Notes
    Route::post('/lead-notes/{lead}', [\App\Http\Controllers\LeadNoteController::class, 'store'])->name('lead-notes.store');
    Route::put('/lead-notes/{note}', [\App\Http\Controllers\LeadNoteController::class, 'update'])->name('lead-notes.update');
    Route::delete('/lead-notes/{note}', [\App\Http\Controllers\LeadNoteController::class, 'destroy'])->name('lead-notes.destroy');
    
    // Losted Leads
    Route::get('/losted-leads', [LeadController::class, 'lostedLeads'])->name('losted-leads');
    Route::get('/export-losted-leads', [LeadController::class, 'lostedLeadExport'])->name('losted-leads.export');

    // Orders
    Route::get('/all-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/export-orders', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/add-order/{lead_id?}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/all-orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/view-order/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/edit-order/{id}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/edit-order/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::patch('/update-order-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/delete-order/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::delete('/delete-orders', [OrderController::class, 'bulkDestroy'])->name('orders.bulk-destroy');
    Route::get('/order-followup/{id}', [FollowupController::class, 'index'])->name('orders.followup');
    Route::post('/order-followup/{id}', [FollowupController::class, 'store'])->name('orders.followup.store');
    
    // Order Notes
    Route::post('/order-notes/{order}', [\App\Http\Controllers\OrderNoteController::class, 'store'])->name('order-notes.store');
    Route::put('/order-notes/{note}', [\App\Http\Controllers\OrderNoteController::class, 'update'])->name('order-notes.update');
    Route::delete('/order-notes/{note}', [\App\Http\Controllers\OrderNoteController::class, 'destroy'])->name('order-notes.destroy');

    // Marketing Orders
    Route::get('/add-marketing-orders', [MarketingOrderController::class, 'index'])->name('marketing-orders');


    // Project
    Route::get('/all-projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/export-projects', [ProjectController::class, 'export'])->name('projects.export');
    Route::get('/project/create/{order_id?}', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/project/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/project/show/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/project/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/project/update/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::post('/project/quick-update/{id}', [ProjectController::class, 'quickUpdate'])->name('projects.quickUpdate');
    Route::delete('/project/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::delete('/project/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulk-destroy');
    Route::get('/project/{project}/tasks', [ProjectTaskController::class, 'index'])->name('projects.tasks');
    Route::post('/project/{project}/tasks', [ProjectTaskController::class, 'store'])->name('projects.tasks.store');
    
    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/export-payments', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('/payments/create/{order_id}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Developer
    Route::get('/add-developer', [DeveloperController::class, 'index'])->name('developer');
    Route::get('/developer/create', [DeveloperController::class, 'create'])->name('developer.create');
    Route::post('/developer/store', [DeveloperController::class, 'store'])->name('developer.store');
    Route::get('/developer/show/{id}', [DeveloperController::class, 'show'])->name('developer.show');
    Route::get('/developer/edit/{id}', [DeveloperController::class, 'edit'])->name('developer.edit');
    Route::put('/developer/update/{id}', [DeveloperController::class, 'update'])->name('developer.update');
    Route::delete('/developer/delete/{id}', [DeveloperController::class, 'destroy'])->name('developer.destroy');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
    Route::post('/my-account', [\App\Http\Controllers\Auth\LoginController::class, 'saleProfileAndPasswordUpdate'])->name('account-settings.update');
    // Attendance
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/give', [\App\Http\Controllers\Admin\AttendanceController::class, 'giveAttendance'])->name('attendance.give');

    // Meetings
    Route::patch('/meetings/{id}/status', [MeetingController::class, 'updateStatus'])->name('meetings.updateStatus');
    Route::get('/export-meetings', [MeetingController::class, 'export'])->name('meetings.export');
    Route::resource('meetings', \App\Http\Controllers\Sale\MeetingController::class);
});
