<?php

use App\Http\Controllers\Developer\AccountSettingController;
use App\Http\Controllers\Developer\DashboardController;
use App\Http\Controllers\Developer\ProjectController;
use App\Http\Controllers\Developer\TaskController;
use App\Http\Controllers\Developer\MeetingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/export-projects', [ProjectController::class, 'export'])->name('projects.export');
    Route::post('/projects/quick-update/{id}', [ProjectController::class, 'quickUpdate'])->name('projects.quickUpdate');
    Route::delete('/project/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulk-destroy');
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
    Route::patch('/meetings/{id}/status', [MeetingController::class, 'updateStatus'])->name('meetings.updateStatus');
    Route::get('/meetings/export', [MeetingController::class, 'export'])->name('meetings.export');
    Route::resource('meetings', MeetingController::class)->except(['edit', 'update' => 'patch']); // Actually I added edit/update manually before? No, resource defines them.
    // Wait, I should use ->only to be specific.
    Route::resource('meetings', MeetingController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    // Attendance
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/give', [\App\Http\Controllers\Admin\AttendanceController::class, 'giveAttendance'])->name('attendance.give');
});
