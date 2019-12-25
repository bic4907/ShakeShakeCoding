<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolveController extends Controller
{
    //
    public function show($problem_num, Request $request) {
        return view('question.solve');
    }
}
