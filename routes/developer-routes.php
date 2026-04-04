<?php

use App\Http\Controllers\Developer\AccountSettingController;
use App\Http\Controllers\Developer\DashboardController;
use App\Http\Controllers\Developer\ProjectController;
use App\Http\Controllers\Developer\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/export-projects', [ProjectController::class, 'export'])->name('projects.export');
    Route::post('/projects/quick-update/{id}', [ProjectController::class, 'quickUpdate'])->name('projects.quickUpdate');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Tasks
    Route::get('/projects/{project}/tasks', [TaskController::class, 'projectTasks'])->name('projects.tasks');
    Route::post('/tasks/{task}/update', [TaskController::class, 'update'])->name('tasks.update');
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.completed');
    Route::get('/tasks/{task}/view', [TaskController::class, 'show'])->name('tasks.show');

    // Account Settings
    Route::get('/my-account', [AccountSettingController::class, 'index'])->name('account-settings');
    Route::post('/my-account/update', [AccountSettingController::class, 'update'])->name('account-settings.update');
    // Meetings
    Route::get('/meetings', [\App\Http\Controllers\Developer\MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/{meeting}', [\App\Http\Controllers\Developer\MeetingController::class, 'show'])->name('meetings.show');
});
