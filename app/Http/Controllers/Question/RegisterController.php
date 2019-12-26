<?php

namespace App\Http\Controllers\Question;

use App\Exceptions\WrongPathException;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Enums\UserType;

class RegisterController extends Controller
{
    function show() {
        return view('question.add',
            [
                'header_title'=>'문제 출제',
                'question' => (new Question)
            ]
        );
    }

    function view(){
        return view('question.view',
            [
                'permission'=>[
                    'isAddable'=>$this->isAddable(),
//                    'isEditable'=>$this->isEditable()
                ]
            ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => 'required|text',
            'professor_id' => 'required|string',
        ])->setAttributeNames([
            'code' => '내용',
            'professor_id' => '작성자',
        ]);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $question = Question::create([
            'code' => $request->post('code'),
            'professor_id' => $user->id,
        ]);
        return redirect(route('question.view',['question_id'=>$question->id]));
    }


    public function destroy($question_id)
    {
        $post = Question::find($question_id);
        $post->delete();

        return redirect(route('question.list'));
    }

    static function isAddable() {
        if(!Auth::user()) return false;
        return Auth::user()->usertype == UserType::Professor;
    }
}
