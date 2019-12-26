<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles) //$roles 를 array로 쓰기 위해 "..." 사용
    {
        if(!Auth::check())
        {
            return redirect('/login');
        }
        foreach ($roles as $role)
        {
            if($request ->user()->
                usertype == $role) //허용한 권한인 경우
            {
                return $next($request); //승인
            }
        }
        abort(403); //403 Error

    }
}
