<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Question;
use App\Submission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function showList(Request $request)
    {
        //find Question List by Prof
        //pagenation

        $user = Auth::user();
        //->where('reg_state','=', RegisterType::Registered)나중에 추가
        $submissions = Submission::where('student_id','=',$user->id)->orderBy('id','desc')->paginate(10);
        $submissionListData = array();

        foreach($submissions as $submission) {
            $question = Question::where('id','=',$submission->question_id)->first();
            $professor = User::where('id', '=', $question->professor_id)->first();

            $submissionListData[] = array(
                'questionId' => $question->id,
                'professorName' => $professor->name,
                'isCorrect' => $submission->isCorrect
            );
        }
        dd($submissionListData);
        return view('auth.student', ['submissionListData' => $submissionListData]);
    }
}
