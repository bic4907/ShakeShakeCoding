<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function showList()
    {
        $questions = Question::orderBy('id','desc')->paginate(10);

        return view('question.list', ['questions' => $questions]);
    }
}
