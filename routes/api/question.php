<?php

use App\Http\Controllers\QuestionController;

Route::get('/questions/catalogue', [QuestionController::class, 'index']);
Route::get('/questions/preload', [QuestionController::class, 'preload']);
Route::post('/questions/massload', [QuestionController::class, 'massLoadQuestions']);
Route::post('/questions/create', [QuestionController::class, 'create']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'delete']);
Route::get('/questions/catalogue/{subject}', [QuestionController::class, 'getBySubject']);
Route::get('/questions/catalogue/random', [QuestionController::class, 'getRandom']);
