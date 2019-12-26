<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Submission\EvalFileController;
use App\Question;
use App\Submission;
use App\TestCase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class StudentController extends Controller
{
    public function showList(Request $request)
    {
        //find Question List by Prof

        $user = Auth::user();
        //->where('reg_state','=', RegisterType::Registered)나중에 추가
        $submissions = Submission::where('student_id','=',$user->id)->orderBy('id','desc')->get();
        $submissionListData = array();

        foreach($submissions as $submission) {
            $question = Question::where('id','=',$submission->question_id)->first();
            $professor = User::where('id', '=', $question->professor_id)->first();

            $submissionArray[] = array(
                'questionId' => $question->id,
                'professorName' => $professor->name,
                'isCorrect' => $submission->isCorrect
            );
            $submissionListData = collect($submissionArray);
        }

        $submissionListData  = $this->paginate($submissionListData);

        return view('auth.student', ['submissionListData' => $submissionListData]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
