<?php

namespace App\Http\Controllers\Problem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolveController extends Controller
{
    //
    public function show($problem_num, Request $request) {
        return view('problem.solve');
    }
}
