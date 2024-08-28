<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardStudentController;
Route::get('/dashboard/user/all', [DashboardController::class, 'all']);
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
Route::get('/dashboard/barchart', [DashboardController::class, 'barchart']);
Route::get('/dashboard/userprogress/{id}', [DashboardController::class, 'userProgress']);
Route::post('/dashboard/linearchart', [DashboardController::class, 'linearchart']);

//student
Route::get('/dashboardstudent/data', [DashboardStudentController::class, 'getData']);
