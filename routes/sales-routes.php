<?php

use App\Http\Controllers\Sale\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sale'])->prefix('sale')->name('sale.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});