<?php

namespace App\Http\Controllers\Problem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function showAnswer(){
        return view('create');
    }

    function addBlinkBlock(Request $request){
        print($request);
    }
}
