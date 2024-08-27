<?php

use App\Http\Controllers\SubjectController;

Route::get('/subject/list', [SubjectController::class, 'index']);
Route::post('/subject/create', [SubjectController::class, 'create']);
Route::get('/subject/find/{id}', [SubjectController::class, 'show']);
Route::post('/subject/update/{id}', [SubjectController::class, 'update']);
Route::get('/subject/delete/{id}', [SubjectController::class, 'destroy']);
Route::get('/subject/restore/{id}', [SubjectController::class, 'restore']);
