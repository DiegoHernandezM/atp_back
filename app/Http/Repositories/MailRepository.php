<?php

namespace App\Http\Repositories;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailRepository
{
    public function sendRegistrationEmail(User $user, $body)
    {
        Mail::to($user->email)->send(new ResetPasswordMail($body));
    }
}
