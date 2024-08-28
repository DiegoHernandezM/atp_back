<?php

namespace App\Http\Repositories;

use App\Models\Subject;

class SubjectRepository
{
    protected $subject;

    public function __construct()
    {
        $this->subject = new Subject();
    }

    public function getAll($request)
    {
        if ($request->trashed === "false") {
            return $this->subject->withCount('questions')->with('latestUserTest')->get();
        } else {
            return $this->subject->onlyTrashed()->withCount('questions')->with('latestUserTest')->get();
        }
    }

    public function create($request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $path = public_path('images');
            $image->move($path, $imageName);
            return $this->subject->create([
                'name' => $request->name,
                'questions_to_test' => $request->questions_to_test,
                'image' => $imageName
            ]);
        }
    }

    public function find($id)
    {
        return $this->subject->find($id);
    }

    public function update($id, $request)
    {
        $subject = $this->find($id);
        if ($subject) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $path = public_path('images');
                $image->move($path, $imageName);

                $subject->name = $request->name;
                $subject->questions_to_test = $request->questions_to_test;
                $subject->image = $imageName;
                $subject->save();
            } else {
                $subject->name = $request->name;
                $subject->questions_to_test = $request->questions_to_test;
                $subject->save();
            }

            return $subject;
        }
    }
}
