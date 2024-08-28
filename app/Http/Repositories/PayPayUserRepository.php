<?php

namespace App\Http\Repositories;

use App\Models\PayPalUser;
use App\Models\User;

class PayPayUserRepository
{
    protected $mPayPal;

    public function __construct()
    {
        return $this->mPayPal = new PayPalUser();
    }

    public function create($request)
    {
        $user = User::find((int)$request->order['reference_id']);
        $user->stand_by = false;
        $user->save();
        return $this->mPayPal->create([
            'user_id' => (int)$request->order['reference_id'],
            'address' => json_encode($request->order['shipping']['address']),
            'amount' => $request->order['amount']['value'],
            'payment_id' => $request->order['payments']['captures'][0]['id'],
            'status' => $request->order['payments']['captures'][0]['status'],
            'create_time' => $request->order['payments']['captures'][0]['create_time']
        ]);
    }
}
