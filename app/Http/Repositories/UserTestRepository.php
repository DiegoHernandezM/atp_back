<?php

namespace App\Http\Repositories;

use App\Models\Question;
use App\Models\Subject;
use App\Models\UserTest;

class UserTestRepository
{
    protected $mUserTest;
    protected $mQuestion;

    public function __construct()
    {
        $this->mUserTest = new UserTest();
        $this->mQuestion = new Question();
    }

    public function getFromId($id)
    {
        $userTest = UserTest::where('id', $id)->where(function ($query) {
            if (auth()->user()->type == 3) {
                $query->where('user_id', auth()->user()->id);
            } else if (auth()->user()->type == 1) {
                $query;
            }
        })->with('subject')->first();
        $questions = Question::whereIn('id', array_keys(json_decode($userTest->questions, true)))->get();

        $returnData = [
            'questions' => $questions,
            'userTest' => $userTest ?? null,
            'subject' => $userTest->subject ?? ['name' => 'Simulacro']
        ];
        return $returnData;
    }

    public function createSingleSubjectTest($request)
    {
        $subject = Subject::find($request->subject_id);
        $hasTests = $this->userHasSubjectTest($request->subject_id);
        if (!empty($hasTests)) {
            $userTest = $this->userHasSubjectTest($request->subject_id);
            $questionIds = array_keys(json_decode($userTest->questions, true));
        } else {
            $questionArray = $this->getQuestionArray($request->subject_id);
            $questionIds =  array_keys($questionArray);
        }

        $questions = Question::whereIn('id', $questionIds)->get();

        $orderedQuestions = $questions->sortBy(function ($question) use ($questionIds) {
            return array_search($question->id, $questionIds);
        })->values();

        if (empty($userTest)) {
            $userTest = $this->mUserTest->create([
                'user_id' => auth()->user()->id,
                'questions' => json_encode($questionArray),
                'last_key' => array_key_first($questionArray),
                'subject_id' => $request->subject_id,
                'completed' => 0,
                'points' => $questions->sum('points')
            ]);
        }
        $returnData = [
            'questions' => $orderedQuestions,
            'userTest' => $userTest ?? null,
            'subject' => $subject
        ];
        return $returnData;
    }

    public function saveFullTestOffline($request)
    {
        if ($request[0]["user_id"] == 0) {
            $questionArray = array_map(function ($item) {
                return array_keys($item)[0];
            }, $request[0]["questions"]);
            $implodedKeys = implode(',', $questionArray);
            $resultArray = array_reduce($request[0]["questions"], function ($carry, $item) {
                foreach ($item as $key => $value) {
                    $carry[$key] = $value ?? "";
                }
                return $carry;
            }, []);
            $questions = Question::whereIn('id', $questionArray)->orderByRaw("FIELD(id, $implodedKeys)")->get();
            if ($request[0]["subject_id"] > 0) {
                $subject = Subject::find($request[0]["subject_id"]);
            } else {
                $subject = ['name' => 'Simulacro'];
            }
            $userTest = $this->mUserTest->create([
                'user_id' => auth()->user()->id,
                'questions' => json_encode($resultArray),
                'last_key' => $request[0]["last_key"],
                'subject_id' => $request[0]["subject_id"],
                'completed' => $request[0]["completed"],
                'points' => $request[0]["points"],
                'percentage' => $request[0]["percentage"],
                'grade' => $request[0]["grade"]
            ]);
            $returnData = [
                'questions' => $questions,
                'userTest' => $userTest ?? null,
                'subject' => $subject
            ];
            return $returnData;
        }
    }

    public function resetTest($request)
    {
        $questionArray = $this->getQuestionArray($request->subject_id);
        $questions = Question::whereIn('id', array_keys($questionArray))->get();
        $subject = Subject::find($request->subject_id);

        $userTest = $this->mUserTest->create([
            'user_id' => auth()->user()->id,
            'questions' => json_encode($questionArray),
            'last_key' => array_key_first($questionArray),
            'subject_id' => $request->subject_id,
            'completed' => 0,
            'points' => $questions->sum('points')
        ]);

        $returnData = [
            'questions' => $questions,
            'userTest' => $userTest ?? null,
            'subject' => $subject
        ];
        return $returnData;
    }


    public function resetSimulationTest()
    {
        $questionArray = $this->getQuestionArray();
        $implodedKeys = implode(',', array_keys($questionArray));
        $questions = Question::whereIn('id', array_keys($questionArray))->orderByRaw("FIELD(id, $implodedKeys)")->get();

        $userTest = $this->mUserTest->create([
            'user_id' => auth()->user()->id,
            'questions' => json_encode($questionArray),
            'last_key' => array_key_first($questionArray),
            'subject_id' => 0,
            'completed' => 0,
            'points' => $questions->sum('points')
        ]);

        $returnData = [
            'questions' => $questions,
            'userTest' => $userTest ?? null,
            'subject' => ['name' => 'Simulacro']
        ];
        return $returnData;
    }

    public function createSimulationTest()
    {
        if (!empty($this->userHasSimulationTest())) {
            $userTest = $this->userHasSimulationTest();
            $tempKeys = array_keys(json_decode($userTest->questions, true));
            $tempKeys = array_map('intval', $tempKeys);
            $implodedKeys = implode(',', $tempKeys);
            $questions = Question::whereIn('id', $tempKeys)
                ->orderByRaw("FIELD(id, $implodedKeys)")->get();
            $questionsTemp = $questions->pluck('id')->toArray();
            $questionsTemp = array_map('intval', $questionsTemp);
            $diff = array_merge(array_diff($questionsTemp, $tempKeys), array_diff($tempKeys, $questionsTemp));

            if (count($diff) > 0) {
                $newArray = json_decode($userTest->questions, true);
                foreach ($diff as $value) {
                    unset($newArray[$value]);
                }
                $userTest->questions = json_encode($newArray);
                $userTest->save();
            }
        } else {
            $questionArray = $this->getQuestionArray();
            $implodedKeys = implode(',', array_keys($questionArray));
            $questions = Question::whereIn('id', array_keys($questionArray))->orderByRaw("FIELD(id, $implodedKeys)")->get();
        }

        if (empty($userTest)) {
            $userTest = $this->mUserTest->create([
                'user_id' => auth()->user()->id,
                'questions' => json_encode($questionArray),
                'last_key' => array_key_first($questionArray),
                'subject_id' => 0,
                'completed' => 0,
                'points' => $questions->sum('points')
            ]);
        }
        $returnData = [
            'questions' => $questions,
            'userTest' => $userTest ?? null,
            'subject' => ['name' => 'Simulacro']
        ];
        return $returnData;
    }

    public function getQuestionArray($subject_id = null)
    {
        if (!$subject_id) {
            $subjects = Subject::all();
            $questions = [];
            foreach ($subjects as $sub) {
                $questions = array_merge($questions, $sub->questions()->inRandomOrder()->limit($sub->questions_to_test)->pluck('id')->toArray());
            }
        } else {
            $questions = $this->mQuestion->where('subject_id', $subject_id)->inRandomOrder()->pluck('id')->toArray();
        }
        $arr = [];
        foreach ($questions as $q) {
            $arr[$q] = '';
        }
        return $arr;
    }

    public function userHasSubjectTest($subject_id)
    {
        return $this->mUserTest->where('user_id', auth()->user()->id)->where('subject_id', $subject_id)->latest()->first();
    }

    public function userHasSimulationTest()
    {
        return $this->mUserTest->where('user_id', auth()->user()->id)->where('subject_id', 0)->latest()->first();
    }

    public function saveAnswer($request)
    {
        $userTest = $this->mUserTest->find($request->user_test_id);
        $questions = json_decode($userTest->questions, true);
        $alreadyAnswered = $questions[$request->question_id] != '' ? true : false;
        $questions[$request->question_id] = $request->answer;
        $countQuestions = count($questions);
        $question = $this->mQuestion->find($request->question_id);
        $userTest->grade += !$alreadyAnswered && $request->answer == $question->answer ? $question->points : 0;
        $answered = 0;
        foreach ($questions as $key => $value) {
            $answered += $value != '' ? 1 : 0;
        }
        $percentAnswered = ($answered * 100) / $countQuestions;
        $userTest->last_key = $request->question_id;
        $userTest->questions = json_encode($questions);
        $userTest->percentage = $percentAnswered;
        $userTest->save();
        return $userTest;
    }

    public function saveAnswerOffline($request)
    {
        $responseData = [];
        foreach ($request->all() as $data) {
            $userTest = $this->mUserTest->find($data['user_test_id']);
            $questions = json_decode($userTest->questions, true);
            $alreadyAnswered = $questions[$data['question_id']] != '' ? true : false;
            $questions[$data['question_id']] = $data['answer'];
            $questions[$data['question_id']];
            $countQuestions = count($questions);
            $question = $this->mQuestion->find($data['question_id']);
            $userTest->grade += !$alreadyAnswered && $data['answer'] == $question->answer ? $question->points : 0;
            $answered = 0;
            foreach ($questions as $key => $value) {
                $answered += $value != '' ? 1 : 0;
            }
            $percentAnswered = ($answered * 100) / $countQuestions;
            $userTest->last_key = $data['question_id'];
            $userTest->questions = json_encode($questions);
            $userTest->percentage = $percentAnswered;
            $userTest->save();
            $responseData[] = $userTest;
        }
        $lastKey = array_key_last($responseData);
        return $responseData[$lastKey];
    }

    public function endTest($request)
    {
        $userTest = $this->userHasSubjectTest($request->subject_id);
        $userTest->completed = 1;
        $userTest->save();
        return $userTest;
    }
    public function endSimulationTest($request)
    {
        $userTest = $this->userHasSimulationTest($request->subject_id);
        $userTest->completed = 1;
        $userTest->save();
        return $userTest;
    }

    public function endTestOffline($request)
    {
        foreach ($request->all() as $data) {
            if ($data['subject_id'] === 0) {
                $userTest = $this->userHasSimulationTest();
                $userTest->completed = 1;
                $userTest->save();
                return $userTest;
            } else {
                $userTest = $this->userHasSubjectTest($data['subject_id']);
                $userTest->completed = 1;
                $userTest->save();
                return $userTest;
            }
        }
    }
}
