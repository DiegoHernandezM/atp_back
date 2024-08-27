<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserTestRepository;
use App\Http\Requests\StoreUserTestRequest;
use App\Http\Requests\UpdateUserTestRequest;
use App\Models\UserTest;
use Illuminate\Http\Request;
use DB;

class UserTestController extends Controller
{

    protected $userTestRepository;

    public function __construct()
    {
        $this->userTestRepository = new UserTestRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->getFromId($id));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    public function getUserTests()
    {
        return UserTest::leftJoin('subjects', 'subjects.id', '=', 'user_tests.subject_id')
            ->select('user_tests.*', 'subjects.name as subject_name', DB::raw('TIMEDIFF(user_tests.updated_at, user_tests.created_at) as duration, "false" as offline'))
            ->where('user_id', auth()->user()->id)->orderByDesc('id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSingleSubjectTest(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->createSingleSubjectTest($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Resets test.
     *
     * @return \Illuminate\Http\Response
     */
    public function resetTest(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->resetTest($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Resets test.
     *
     * @return \Illuminate\Http\Response
     */
    public function resetSimulationTest()
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->resetSimulationTest());
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSimulationTest()
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->createSimulationTest());
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Guarda respuesta de usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAnswer(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->saveAnswer($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Guarda respuesta de usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAnswerOffline(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->saveAnswerOffline($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Guarda examen offline
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveFullTestOffline(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->saveFullTestOffline($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Finaliza el test.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endTest(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->endTest($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Finaliza el test offline.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endTestsOffline(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->endTestOffline($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Finaliza el test.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endSimulationTest(Request $request)
    {
        try {
            return ApiResponses::okObject($this->userTestRepository->endSimulationTest($request));
        } catch (\Throwable $th) {
            return ApiResponses::internalServerError($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserTestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserTestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserTest  $userTest
     * @return \Illuminate\Http\Response
     */
    public function show(UserTest $userTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserTest  $userTest
     * @return \Illuminate\Http\Response
     */
    public function edit(UserTest $userTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserTestRequest  $request
     * @param  \App\Models\UserTest  $userTest
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserTestRequest $request, UserTest $userTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserTest  $userTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserTest $userTest)
    {
        //
    }
}
