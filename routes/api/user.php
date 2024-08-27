<?php

use App\Http\Controllers\UserController;

Route::get('/user/all', [UserController::class, 'all']);
Route::get('/user/get/{id}', [UserController::class, 'get']);
Route::post('/user/update/{id}', [UserController::class, 'update']);
Route::post('/user/delete/{id}', [UserController::class, 'delete']);
Route::post('/user/restore/{id}', [UserController::class, 'restore']);
Route::post('/user/changepassword', [UserController::class, 'change']);
Route::get('/user/profile', [UserController::class, 'profile']);
Route::post('/user/devices/{id}', [UserController::class, 'restoreDevices']);
