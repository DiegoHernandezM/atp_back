<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardRepository;
use Illuminate\Http\Request;


class DashboardController
{
    protected $rDashboard;

    public function __construct()
    {
        $this->rDashboard = new DashboardRepository();
    }

    /**
     * Obtiene usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            return ApiResponses::okObject($this->rDashboard->getAllUsers());
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Obtiene estadisticas para el dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function stats()
    {
        try {
            return ApiResponses::okObject($this->rDashboard->getStats());
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Obtiene estadisticas para el bar chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function barchart()
    {
        try {
            return ApiResponses::okObject($this->rDashboard->getBarChart());
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Obtiene detalle del usuario.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function userProgress($id)
    {
        try {
            return ApiResponses::okObject($this->rDashboard->getUserProgress($id));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }

    /**
     * Obtiene balance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function linearchart(Request $request)
    {
        try {
            return ApiResponses::okObject($this->rDashboard->getBalance($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e);
        }
    }
}
