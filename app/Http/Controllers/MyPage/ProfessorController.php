<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Question;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use RegisterType;

class ProfessorController extends Controller
{
    public function showList(Request $request)
    {
        //find Question List by Prof

        $user = Auth::user();
        //->where('reg_state','=', RegisterType::Registered) 나중에 추가
        $questions = Question::where('professor_id','=',$user->id)->orderBy('id','desc')->get();
        $questionListData = array();

        foreach($questions as $question) {
            $questionId = $question->id;
            $submissionsCount = Submission::where('question_id','=',$question->id)->count();
            $correctSubmissionsCount = Submission::where('question_id','=',$question->id)->where('isCorrect','=',true)->count();
            if($submissionsCount > 0)
                $correctRate = $correctSubmissionsCount/$submissionsCount;
            else $correctRate = 0;
            $studentCount = Submission::where('question_id','=',$question->id)->distinct('student_id')->count();

            $questionArray[] = array(
                'questionId' => $questionId,
                'correctRate' => $correctRate,
                'studentCount' => $studentCount
            );

            $questionListData = collect($questionArray);
        }

        $questionListData = $this->paginate($questionListData);

        return view('auth.professor', ['questionListData' => $questionListData]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
