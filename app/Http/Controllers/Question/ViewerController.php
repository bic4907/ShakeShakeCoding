<?php

namespace App\Http\Controllers\Question;

use App\Enums\UserType;
use App\Http\Controllers\API\MD2HTMLController;
use App\Notice;
use App\NoticeView;
use App\Question;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Competition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\PaginateController;

class ViewerController extends Controller
{
    public function show($question_id) {
        $question = Question::findOrFail($question_id);

        $question->code = MD2HTMLController::convert($question->code);


        if(Auth::check())
        {
            try {
                $this->addView((int)$question_id);
            } catch (QueryException $exception){
                return view('question.view',
                    ['question'=> $question,
                        'permission'=>[
                            'isAddable'=>RegisterController::isAddable()
                        ]
                    ]);
            }
        }

        return view('question.view',
            ['question'=> $question,
                'permission'=>[
                    'isAddable'=>RegisterController::isAddable(),
                ]
            ]);
    }

    public function showlist(Request $request){
        $page = intval($request->get('page'));
        if($page == null or $page < 0) {
            $page = 1;
        }
        $question = Question::orderBy('id','desc')->paginate(10);
        $questionCount = Question::count();
        $data = PaginateController::getPageData($page, $question->lastPage());
        return view('question.list', [
            'questions' => $question,
        ]);
    }

}
