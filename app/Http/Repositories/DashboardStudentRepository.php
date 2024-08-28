<?php

namespace App\Http\Repositories;

use App\Models\UserTest;
use Illuminate\Support\Facades\DB;

class DashboardStudentRepository
{
    protected $mUserTest;
    public function __construct()
    {
        return $this->mUserTest = new UserTest();
    }

    public function getInfo()
    {
        $test = $this->mUserTest->where('user_id', auth()->user()->id)
            ->where('subject_id', 0)->where('completed', true)->count();
        $subjectsActives = $this->mUserTest->where('user_id', auth()->user()->id)
            ->where('subject_id', '>', 0)->where('completed', false)->count();
        $subjects = $this->mUserTest->where('user_id', auth()->user()->id)
            ->where('subject_id', '>', 0)->where('completed', true)->count();
        $quizz = DB::select('CALL quizz_subject_procedure(' . auth()->user()->id . ')');

        $aSubjects = [];
        $aReps = [];

        foreach ($quizz as $val) {
            $aSubjects[] = $val->name;
            $aReps[] = $val->repeticion;
        }
        return [
            'test' => $test,
            'subjectsActives' => $subjectsActives,
            'subjects' => $subjects,
            'aSubjects' => $aSubjects,
            'aReps' => $aReps
        ];
    }
}
