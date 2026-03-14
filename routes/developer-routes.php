<?php

use App\Http\Controllers\Developer\AccountSettingController;
use App\Http\Controllers\Developer\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
});
