<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Block;
use App\Submission;
use App\User;
use Illuminate\Support\Facades\DB;

class ViewerController extends Controller
{
    public function show($question_id)
    {
        $data = DB::table('submissions')
            ->leftJoin('users', 'users.id', '=', 'submissions.student_id')
            ->distinct('users.id')->where('submissions.question_id', $question_id)->select('users.name', 'users.id')->get();

        for($i=0;$i<sizeof($data);$i++){
            $submitCount[$i] = Submission::where('question_id', $question_id)->where('student_id', $data[$i]->id)->count();
            $correct[$i] = Submission::where('question_id', $question_id)->where('student_id', $data[$i]->id)->select('isCorrect')->first();
        }

        return view('question.submission', ['question_id'=>$question_id, 'student'=>$data, 'submitCount'=>$submitCount,
            'correct'=>$correct]);
    }

    public function getBlockList($question_num) {

        $blocks = Block::where('question_id', $question_num)->select('content', 'type as block_type')->get();

        return $blocks;
    }
}
