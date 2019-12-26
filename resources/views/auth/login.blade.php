@extends('layouts.app')

@section('title')
    Log In
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="container">
    <div class="col-sm-6 mx-auto">
        <div class="card mt-5">
            <div class="card-header">
                로그인
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class = "alert alert-success">
                        {{session('success')}}
                    </div>
                @endif
                @if($errors->has('user_id') || $errors->has('password'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        아이디 또는 비밀번호가 맞지 않습니다
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                @endif
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                        <input id="user_id" type="text" class="form-control" name="user_id" value="{{ old('user_id') }}" placeholder="아이디를 입력하세요" required autofocus>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control" name="password" placeholder="비밀번호를 입력하세요" required>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 나를 기억할까요?
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                로그인
                            </button>

{{--                            <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                비밀번호를 잊어버리셨나요?--}}
{{--                            </a>--}}
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="cointainer my-5">

        <div class="col-sm-4 mx-auto text-center">
            <hr class="mt-5 mt-2">
            <span class="mr-1">아직 회원이 아니신가요?</span>
            <a href="{{ route('register') }}">
                회원가입
            </a>
        </div>
    </div>

@endsection
