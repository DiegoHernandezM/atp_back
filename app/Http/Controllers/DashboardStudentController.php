<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardStudentRepository;
use Illuminate\Http\Request;

class DashboardStudentController extends Controller
{
    public function getData(DashboardStudentRepository $repository)
    {
        try {
            return ApiResponses::okObject($repository->getInfo());
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
