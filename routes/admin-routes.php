<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\SalesPersonController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/add-sales-person', [SalesPersonController::class, 'index'])->name('sales-person');

    Route::get('/add-developer', [DeveloperController::class, 'index'])->name('developer');
});