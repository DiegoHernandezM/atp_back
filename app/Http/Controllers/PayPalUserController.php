<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PayPayUserRepository;
use Illuminate\Http\Request;

class PayPalUserController extends Controller
{
    public function create(Request $request, PayPayUserRepository $rPaypal)
    {
        try {
            return ApiResponses::okObject($rPaypal->create((object)$request));
        } catch (\Exception $e)
        {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
