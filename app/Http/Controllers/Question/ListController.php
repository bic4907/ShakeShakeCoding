<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function showList()
    {
//        $questions = Question::orderBy('id','desc')->paginate(10);

        $questions = DB::table('questions')
            ->leftJoin('users', 'questions.professor_id', '=', 'users.id')
            ->orderBy('questions.id', 'desc')->paginate(10);

        return view('question.list', ['questions' => $questions]);
    }
}
