<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleDashboardController;

Route::middleware(['auth:sale'])->prefix('sale')->name('sale.')->group(function () {
    Route::get('/dashboard', [SaleDashboardController::class, 'index'])->name('dashboard');
});