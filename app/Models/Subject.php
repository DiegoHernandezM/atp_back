<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'description', 'image', 'questions_to_test'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userTests()
    {
        return $this->hasMany(UserTest::class);
    }

    public function latestUserTest()
    {
        return $this->hasOne(UserTest::class)->where('user_id', auth()->user()->id)->latest();
    }
}
