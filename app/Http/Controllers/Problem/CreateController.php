<?php

namespace App\Http\Controllers\Problem;

use App\Block;
use App\Enums\BlockType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class CreateController extends Controller
{
    function showAnswer($problem_num){
        return view('question/create', ['problem_num'=>$problem_num]);
    }

    function addBlinkBlock($problem_num, Request $request){

        $temp = explode('!!]]', $request->text);
        for($i=0;$i<sizeof($temp);$i++){
            $result[$i] = strstr($temp[$i], '[[!!');
            $result[$i] = substr($result[$i], 4);

            $todo_block = new Block();
            $todo_block->question_id = $problem_num;
            $todo_block->type = '0';
            $todo_block->content = $result[$i];
            $todo_block->save();
        }

        $block = $request->text;

        for($i=0;$i<sizeof($temp)-1;$i++){
            $result[$i] = '[[!!'.$result[$i].'!!]]';
            $temp_block = explode($result[$i], $block);
            $block = $temp_block[0].$temp_block[1];
        }

        $block = explode('<br>', $block);

        echo(strlen($block[5]));
        echo($block[5]);

        for($i=0;$i<sizeof($block);$i++){
            $todo_block = new Block();
            $todo_block->question_id = $problem_num;
            $todo_block->type = '1';
            $todo_block->content = $block[$i];
            $todo_block->save();
        }
    }
}
