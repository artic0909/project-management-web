<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderInquiryController;

Route::get('/', function () {
    $services = \App\Models\Service::all();
    $sources = \App\Models\Source::all();
    $plans = \App\Models\Plan::all();
    $paymentStatuses = \App\Models\Status::where('type', 'payment')->get();
    return view('welcome', compact('services', 'sources', 'plans', 'paymentStatuses'));
})->name('home');

Route::post('/order-inquiry', [OrderInquiryController::class, 'store'])->name('order.inquiry.store');

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
