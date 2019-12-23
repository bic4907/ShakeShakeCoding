<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Question;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RegisterType;

class ProfessorController extends Controller
{
    public function showList(Request $request)
    {
        //find Question List by Prof
        //pagenation

        $user = Auth::user();
        //->where('reg_state','=', RegisterType::Registered) 나중에 추가
        $questions = Question::where('professor_id','=',$user->id)->orderBy('id','desc')->paginate(10);
        $questionListData = array();

        foreach($questions as $question) {
            $questionId = $question->id;
            $submissionsCount = Submission::where('question_id','=',$question->id)->count();
            $correctSubmissionsCount = Submission::where('question_id','=',$question->id)->where('isCorrect','=',true)->count();
            if($submissionsCount > 0)
                $correctRate = $correctSubmissionsCount/$submissionsCount;
            else $correctRate = 0;
            $studentCount = Submission::where('question_id','=',$question->id)->distinct('student_id')->count();

            $questionListData[] = array(
                'questionId' => $questionId,
                'correctRate' => $correctRate,
                'studentCount' => $studentCount
            );
        }

        dd($questionListData);

        return view('auth.professor', ['questionListData' => $questionListData]);
    }
}
