<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Question;

class SolveController extends Controller
{

    /**
     * 제출파일 모델을 생성하는 부분
     *
     * @param $question_num
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function makeSubmission($question_num) {

        if(!Auth::user()) abort(401);

        $submission = new Submission;
        $submission->question_id = $question_num;
        $submission->student_id = Auth::id();
        $submission->save();

        return redirect(
            route('submission.view', [
                'question_num'=>$question_num,
                'submission_num'=>$submission->id
            ])
        );
    }


    public function showSubmission($question_num, $submission_num) {

        $submission = Submission::findOrFail($submission_num);
        $question = Question::findOrFail($submission->question_id);

        $question->gradingUrl = route('submission.grade',
            [
                'question_num'=>$question_num,
                'submission_num'=>$submission_num
            ]
        );
        $question->blockUrl = route('question.blocklist',
            [
                'question_num'=>$question_num
            ]
        );



        return view('question.solve', [
            'question'=>$question,
            'submission'=>$submission,
        ]);
    }

}
