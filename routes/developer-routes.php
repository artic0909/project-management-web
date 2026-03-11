<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeveloperDashboardController;

Route::middleware(['auth:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('dashboard');
});