<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    public function show($question_num)
    {

        return view('question.view');
    }
}
