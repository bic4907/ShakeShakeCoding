<?php

namespace App\Http\Controllers\Problem;

use App\Enums\BlockType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function showAnswer($problem_num){
        return view('problem/create', ['problem_num'=>$problem_num]);
    }

    function addBlinkBlock($problem_num, Request $request){
        if(!$request->blink){   //블
            dd($problem_num);
            $todo_block = new Block();
            $todo_block->question_id = $problem_num;
            $todo_block->enum = BlockType::Block;
            $todo_block->text = $request->block;
        }
        else{
//            $todo->question_id = ;
//            $todo->enum = Blocktype::Input;
//            $todo->text = $request->block;

        }
    }
}
