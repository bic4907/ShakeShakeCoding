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
        $questions = $this->getQuestions($user);
        $questionListData = array();

        foreach($questions as $question) {
            $questionId = $question->id;
            $submissionsCount = $this->getSubmissionCount($questionId);
            $correctSubmissionsCount = $this->getCorrectSubmissionCount($questionId);
            if($submissionsCount > 0)
                $correctRate = $correctSubmissionsCount/$submissionsCount;
            else $correctRate = 0;
            $studentCount = $this->getStudentCount($questionId);

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

    public function getQuestions($user)
    {
        $questions = Question::where('professor_id','=',$user->id)->orderBy('id','desc')->get();
        return $questions;
    }

    public function getSubmissionCount($question_id)
    {
        $submissionsCount = Submission::where('question_id','=',$question_id)->count();
        return $submissionsCount;
    }

    public function getCorrectSubmissionCount($question_id)
    {
        $correctSubmissionsCount = Submission::where('question_id','=',$question_id)->where('isCorrect','=',true)->count();
        return $correctSubmissionsCount;
    }

    public function getStudentCount($question_id)
    {
        $studentCount = Submission::where('question_id','=',$question_id)->distinct('student_id')->count();
        return $studentCount;
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
