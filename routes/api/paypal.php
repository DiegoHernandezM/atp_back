<?php

use App\Http\Controllers\PayPalUserController;

Route::post('/paypal/create', [PayPalUserController::class, 'create']);

