<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::post('/admin/login', [\App\Http\Controllers\Auth\LoginController::class, 'adminLogin'])->name('admin.login.post');
Route::post('/sale/login', [\App\Http\Controllers\Auth\LoginController::class, 'saleLogin'])->name('sale.login.post');
Route::post('/developer/login', [\App\Http\Controllers\Auth\LoginController::class, 'developerLogin'])->name('developer.login.post');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin-routes.php';
require __DIR__.'/sales-routes.php';
require __DIR__.'/developer-routes.php';
