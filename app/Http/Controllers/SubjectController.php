<?php

namespace App\Http\Controllers;

use App\Http\Repositories\SubjectRepository;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * @param Request $request
     * @param SubjectRepository $rSubject
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SubjectRepository $rSubject)
    {
        try {
            return ApiResponses::okObject($rSubject->getAll($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getmessage());
        }
    }

    /**
     * @param Request $request
     * @param SubjectRepository $rSubject
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, SubjectRepository $rSubject)
    {
        try {
            return ApiResponses::okObject($rSubject->create($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getmessage());
        }
    }

    /**
     * @param $id
     * @param SubjectRepository $rSubject
     * @return \Illuminate\Http\Response
     */
    public function show($id, SubjectRepository $rSubject)
    {
        try {
            $subject = $rSubject->find($id);
            if ($subject) {
                return ApiResponses::okObject($subject);
            } else {
                return ApiResponses::notFound();
            }
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param SubjectRepository $rSubject
     * @return void
     */
    public function update($id, Request $request, SubjectRepository $rSubject)
    {
        try {
            return $rSubject->update($id, $request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param SubjectRepository $rSubject
     * @return void
     */
    public function destroy(Subject $id)
    {
        try {
            $id->delete();
            return ApiResponses::ok();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            Subject::withTrashed()->find($id)->restore();
            return ApiResponses::ok();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
