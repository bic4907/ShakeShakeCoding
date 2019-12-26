<?php

namespace App\Http\Controllers\Problem;

use App\Block;
use App\Enums\BlockType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Question;

class CreateController extends Controller
{
    function inputAnswer($problem_num, Request $request){
                echo($request->text);

//        $description = Question::where('id', $problem_num)->select('code')->first();
    }

    function showAnswer($problem_num){
        $description = Question::where('id', $problem_num)->select('code')->first();
        return view('question/create', ['problem_num'=>$problem_num, 'description'=>$description->code]);
    }

    function addBlinkBlock($problem_num, Request $request){

        $temp = explode('!!]]', $request->text);
        for($i=0;$i<sizeof($temp);$i++){
            $result[$i] = strstr($temp[$i], '[[!!');
            $result[$i] = substr($result[$i], 4);

            if(strlen($result[$i])>0) {
//                echo('block = ' . $result[$i] . ' size = ' . strlen($result[$i]) . '<br>');

                $todo_block = new Block();
                $todo_block->question_id = $problem_num;
                $todo_block->type = '0';
                $todo_block->content = $result[$i];
                $todo_block->save();
            }
        }

        $block = $request->text;

        for($i=0;$i<sizeof($temp)-1;$i++){
            $result[$i] = '[[!!'.$result[$i].'!!]]';
            $temp_block = explode($result[$i], $block);
            $block = $temp_block[0].$temp_block[1];
        }

        $block = explode('<br>', $block);

        for($i=0;$i<sizeof($block);$i++){
            if(strlen($block[$i])>1) {

//                echo('blink = '.ltrim($block[$i]).' size = '.strlen($block[$i]).'<br>');

                $todo_block = new Block();
                $todo_block->question_id = $problem_num;
                $todo_block->type = '1';
                $todo_block->content = trim($block[$i]);
                $todo_block->save();
            }
        }
    }
}
