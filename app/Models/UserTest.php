<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'questions', 'last_key', 'completed', 'subject_id', 'points', 'grade'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getParsedAttribute()
    {
        $parse = str_replace('{', '[{', $this->attributes['questions']);
        $parse = str_replace('}', '}]', $parse);
        $parse = str_replace(',', '},{', $parse);
        return json_decode($parse, true);
    }

    protected $appends = ['parsed'];
}
