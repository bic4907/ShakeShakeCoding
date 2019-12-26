<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;
use App\Block;

class ViewerController extends Controller
{
    public function show($question_num)
    {
        return view('question.view');
    }

    public function getBlockList($question_num) {

        $blocks = Block::where('question_id', $question_num)->select('content')->get();

        return $blocks;
    }
}
