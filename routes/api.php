<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    require base_path('routes/api/question.php');
    require base_path('routes/api/user.php');
    require base_path('routes/api/usertest.php');
    require base_path('routes/api/dashboard.php');
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['json.response']], function () {

    // public routes
    require base_path('routes/public.php');
    require base_path('routes/api/paypal.php');

    // private routes
    Route::middleware('auth:api')->group(function () {
        // Route::post('/logout', [AuthController::class, 'logout']);
    });
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    require base_path('routes/api/subject.php');
});
