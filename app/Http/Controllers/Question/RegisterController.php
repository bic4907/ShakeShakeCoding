<?php

namespace App\Http\Controllers\Question;

use App\Block;
use App\Exceptions\WrongPathException;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    function show() {
        return view('question/add', ['header_title'=>'문제 출제']
        );
    }

    public function add(Request $request)
    {
        $todo_code = new Question();
        $todo_code->code = $request->text;
        $todo_code->professor_id = Auth::user()->id;

        $todo_code->save();

        return redirect()->route('question.edit' , ['problem_num'=>$todo_code->id, 'description'=>$todo_code->code, 'header_title'=>'문제 만들기']);
    }

    function editAnswer($problem_num){
        $description = Question::where('id', $problem_num)->select('code')->first();
//        echo($description->code);
        return view('question.edit', ['problem_num'=>$problem_num, 'description'=>$description->code, 'header_title'=>'문제 만들기']);
    }

    function addBlinkBlock($problem_num, Request $request){
        $request->text = str_replace('&nbsp;', ' ', $request->text);
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
