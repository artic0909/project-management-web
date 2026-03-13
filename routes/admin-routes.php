<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\SalesPersonController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SourceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sales Person
    Route::get('/add-sales-person', [SalesPersonController::class, 'index'])->name('sales-person');

    // Developer
    Route::get('/add-developer', [DeveloperController::class, 'index'])->name('developer');

    // Services
    Route::get('/add-services', [ServiceController::class, 'index'])->name('services');

    // Leads
    Route::get('/add-leads', [LeadController::class, 'index'])->name('leads');

    // Sources
    Route::get('/add-sources', [SourceController::class, 'index'])->name('sources');
});