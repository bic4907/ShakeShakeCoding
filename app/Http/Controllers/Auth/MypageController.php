<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function show() {

        $user = Auth::user();
        //$user = User::where('id',1)->first();
        return View('auth.mypage', array('name'=>$user->name, 'email'=>$user->email,'userType'=>$user->userType));
    }
}
