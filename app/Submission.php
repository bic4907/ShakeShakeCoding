<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'answer', 'isCorrect', 'question_id', 'student_id',
    ];
}
