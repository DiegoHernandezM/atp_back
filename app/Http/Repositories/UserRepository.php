<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Models\UserDevice;
use Auth;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Hash;


class UserRepository
{

    protected $mUser;
    protected $mUserDevices;

    public function __construct()
    {
        $this->mUser = new User();
        $this->mUserDevices = new UserDevice();
    }

    public function getAllUsers($request)
    {
        if($request->trashed === "false") {
            return $this->mUser->where('stand_by', false)->with('payments')->get();
        } else {
            return $this->mUser->onlyTrashed()->with('payments')->get();
        }
    }

    public function getUserById($id)
    {
        return $this->mUser->withTrashed()->with('payments')->with('devices')->find($id);
    }

    public function createUser($request)
    {
        //TODO agregar funcion para crear contraseña y enviarla por email
        return $this->mUser->create([
            'name' => $request->name,
            'email' => $request->email,
            'school' => $request->type_id === 1  ? null : $request->school,
            'type_id' => $request->type_id === 1 ? User::TYPES['admin'] : User::TYPES['student'],
            'expires_at' => $request->type_id !== 1 ? $request->expires_at : null,
            'password' => $request->password === null ? Hash::make('123456') : Hash::make($request->password),
            'created_by_admin' => $request->password === null,
            'stand_by' => $request->password !== null
        ]);
    }

    public function updateUser($id, $request)
    {
        $user = $this->getUserById($id);

        if (empty($user)) {
            return false;
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->school = $request->school;
            $user->type_id = $request->type_id === 1 ? User::TYPES['admin'] : User::TYPES['student'];
            $user->expires_at = $user->type_id !== 1 ? $request->expires_at : null;
            $user->save();
            return true;
        }
    }

    public function deleteUser($id)
    {
        $this->mUser->where('id', $id)->delete();
        return 'Usuario eliminado';
    }

    public function restoreUser($request, $id)
    {
        $user = $this->mUser->withTrashed()->where('id', $id)->first();
        if ($user) {
            $user->created_by_admin = true;
            $user->expires_at = Carbon::parse($request->expires);
            $user->save();
            $user->restore();
        }
        return 'Usuario restaurado';
    }

    public function changePassword($request)
    {
        if (!(Hash::check($request->old_password, Auth::user()->password))) {
            return response()->json(['message' => 'La contraseña no coincide con los registros'], 400);
        }
        if (strcmp($request->old_password, $request->new_password) === 0) {
            return response()->json(['message' => 'La nueva contraseña tiene que ser diferente a la anterior'], 400);
        }
        $oValidator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if ($oValidator->fails()) {
            return response()->json(['message' =>  'La nueva contraseña tiene que ser igual o mayor de 6 caracteres'], 400);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $user;
    }

    public function createPassword($name)
    {
        $password = Str::random(12);

        return $password;
    }

    public function findByEmail($request)
    {
        $user = $this->mUser->withTrashed()->with(['payments'])->where('email', '=', $request->email)->first();
        if ($user) {
            if ($user->deleted_at !== null) {
                $user->restore();
            }
            if (count($user->payments) === 0) {
                return $user;
            }
        }
    }

    public function getProfile()
    {
        return Auth::user();
    }

    public function restoreUserDevices($id)
    {
        $this->mUserDevices->where('user_id', $id)->delete();
        return 'Dispositivos eliminados';
    }
}
