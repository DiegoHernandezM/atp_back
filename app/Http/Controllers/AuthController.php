<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Repositories\MailRepository;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Log;

class AuthController extends Controller
{
    protected $mUser;
    protected $rMail;
    public function __construct()
    {
        $this->mUser = new User();
        $this->rMail = new MailRepository();
    }

    public function login(Request $request)
    {
        try {
            $user = $this->mUser->where('email', $request->username)->first();
            if ($user) {
                if ($user->type_id === User::TYPES['student'] || $user->created_by_admin === true) {
                    $payments = $user->payments;
                    if (count($payments) > 0) {
                        if ($payments[0]->create_time < Carbon::now()->format('Y-m-d H:i:s')) {
                            $user->delete();
                            return ApiResponses::badRequest("El acceso ha expirado");
                        }
                    } elseif ($user->created_by_admin === false) {
                        $user->delete();
                        return ApiResponses::badRequest("Sin pago registrado");
                    } else {
                        if ($user->expires_at < Carbon::now()->format('Y-m-d H:i:s')) {
                            $user->delete();
                            return ApiResponses::badRequest("El acceso ha expirado");
                        }
                    }
                }
                if (Hash::check($request->password, $user->password)) {
                    if ($user->type_id != User::TYPES['admin']) {
                        $deviceId = $request->device_id;
                        $deviceCount = UserDevice::where('user_id', $user->id)->count();
                        $deviceExists = UserDevice::where('user_id', $user->id)->where('device_id', $deviceId)->exists();
                        if ($deviceCount >= 2 && !$deviceExists) {
                            return ApiResponses::badRequest("Has excedido el número de dispositivos comunícate con el administrador");
                        }
                        if (!$deviceExists) {
                            UserDevice::create([
                                'user_id' => $user->id,
                                'device_id' => $deviceId,
                            ]);
                        }
                    }
                    $token = $user->createToken('Laravel Password Grant Client');
                    $data = [
                        'access_token' => $token->plainTextToken,
                        'accessToken' => $token->plainTextToken,
                        'user' => [
                            'displayName' => $user->name,
                            'permissions' => $user->permissions,
                            'type_id' => $user->type_id
                        ]
                    ];
                    return ApiResponses::okObject($data);
                } else {
                    return ApiResponses::badRequest("Contraseña incorrecta");
                }
            } else {
                return ApiResponses::notFound('El usuario no existe');
            }
        } catch (\Exception $e) {
            Log::error('Error en ' . __METHOD__ . ' línea ' . $e->getLine() . ':' . $e->getMessage());
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function verifyemail(Request $request)
    {
        $token = $request->token;
        if ($token) {
            $user = $this->mUser->where('device_key', $token)
                ->whereNull('email_verified_at')
                ->first();
        }
        if (!empty($user)) {
            $user->email_verified_at = new \DateTime();
            $user->save();
        } else {
            $response = 'Bad Request.';
            return response($response, 400);
        }
        $response = 'You have succesfully verified your email!';
        return response($response, 200);
    }

    public function logout(Request $request)
    {
        $device = $request->device_id;
        $exist = UserDevice::where('device_id', $device)->first();
        if ($exist) {
            $exist->delete();
        }
        return ApiResponses::ok();
    }

    public function resetPassword(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email|exists:users'
            ]);
            if ($validate->fails()) {
                return ApiResponses::badRequest('Verifique su correo');
            }
            $user = $this->mUser->where('email', $request->email)->first();
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();
            $body = [
                $user,
                $newPassword
            ];
            $this->rMail->sendRegistrationEmail($user, $body);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function submitResetPassword(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'password' => 'required|string|min:6',
            ]);

            if ($validate->fails()) {
                return ApiResponses::badRequest(implode(",", $validate->errors()->all()));
            }

            $updatePassword = DB::table('password_resets')
                ->where(['token' => $request->token])
                ->first();

            if (!$updatePassword) {
                return ApiResponses::notFound('No se encontraron registro de solicitud');
            }

            User::where('email', $updatePassword->email)->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();
            return ApiResponses::ok('Contraseña actualizada');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
