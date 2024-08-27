<?php

use App\Http\Controllers\UserTestController;

Route::get('/usertest/getfromuser', [UserTestController::class, 'getUserTests']);
Route::get('/usertest/simulation/create', [UserTestController::class, 'createSimulationTest']);
Route::get('/usertest/simulation/reset', [UserTestController::class, 'resetSimulationTest']);
Route::get('/usertest/simulation/end', [UserTestController::class, 'endSimulationTest']);
Route::get('/usertest/singlesubject/create', [UserTestController::class, 'createSingleSubjectTest']);
Route::get('/usertest/singlesubject/reset', [UserTestController::class, 'resetTest']);
Route::get('/usertest/singlesubject/end', [UserTestController::class, 'endTest']);
Route::post('/usertest/saveanswer', [UserTestController::class, 'saveAnswer']);
Route::post('/usertest/saveansweroffline', [UserTestController::class, 'saveAnswerOffline']);
Route::get('/usertest/find/{id}', [UserTestController::class, 'find']);
Route::post('/usertest/endtests/offline', [UserTestController::class, 'endTestsOffline']);
Route::post('/usertest/savefulltestoffline', [UserTestController::class, 'saveFullTestOffline']);
