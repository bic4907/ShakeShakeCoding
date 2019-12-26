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
            ->orderBy('questions.id', 'desc')->distinct('questions.id')
            ->select('users.name', 'questions.id', 'questions.title', 'questions.created_at')->paginate(10);

        return view('question.list', ['questions' => $questions]);
    }
}
