<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;
use App\Block;
use App\Submission;
use App\Http\Controllers\MyPage\ProfessorController;

class ViewerController extends Controller
{
    public function show($question_id)
    {
        $student = ProfessorController::getStudentList($question_id);

        for($i=0;$i<sizeof($student);$i++){
            $submitCount[$i] = ProfessorController::getStudentSubmissionCount($question_id, $student[$i]->student_id);
            $correctSubmission[$i] = sizeof(ProfessorController::getStudentCorrectSubmission($question_id, $student[$i]->student_id)) ? 'O' : 'X';
        }

        return view('question.submission', ['question_id'=>$question_id, 'student'=>$student, 'submitCount'=>$submitCount,
            'correct'=>$correctSubmission]);
    }

    public function getBlockList($question_num) {

        $blocks = Block::where('question_id', $question_num)->select('content')->get();

        return $blocks;
    }
}
