<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $rUser;

    public function __construct()
    {
        $this->rUser = new UserRepository();
    }

    /**
     * Obtiene usuarios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        try {
            return ApiResponses::okObject($this->rUser->getAllUsers($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Obtiene usuario por id.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        try {
            return ApiResponses::okObject($this->rUser->getUserById($id));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Guarda usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            return ApiResponses::okObject($this->rUser->createUser($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Edita un usuario.
     *
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update($id, Request $request)
    {
        try {
            $user = $this->rUser->updateUser($id, $request);
            if (!$user) {
                return ApiResponses::notFound('No se encontró el usuario');
            }
            return ApiResponses::ok();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Elimina un usuario
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        try {
            return ApiResponses::okObject($this->rUser->deleteUser($id));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Recupera un usuario
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */

    public function restore(Request $request, $id)
    {
        try {
            return ApiResponses::okObject($this->rUser->restoreUser($request, $id));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Cambia contraseña
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        try {
            return $this->rUser->changePassword($request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    public function findEmail(Request $request)
    {
        try {
            return ApiResponses::okObject($this->rUser->findByEmail($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    public function profile()
    {
        try {
            return ApiResponses::okObject($this->rUser->getProfile());
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function restoreDevices(Request $request, $id)
    {
        try {
            return ApiResponses::okObject($this->rUser->restoreUserDevices($id));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }
}
