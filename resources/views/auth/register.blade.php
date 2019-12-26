@extends('layouts.app')

@section('title')
    Sign In
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container">
        <div class="col-sm-6 mx-auto">
            <div class="card mt-5">
                <div class="card-header">
                    회원가입
                </div>

                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label>아이디</label>
                            <input type="text" class="form-control col-9 {{ $errors->has('user_id') ? ' is-invalid' : '' }}" name="user_id" placeholder="아이디을 입력하세요" value="{{ old('user_id') }}" required autofocus>
                            @if ($errors->has('user_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_id') }}
                                </div>
                            @endif
                            <small class="form-text text-muted">로그인하실 아이디를 입력해주세요. 추후 변경이 불가합니다.</small>
                        </div>

                        <div class="form-group">
                            <label>비밀번호</label>
                            <input type="password" class="form-control col-9 {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="비밀번호를 입력하세요" value="" required>
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif

                            <input id="password-confirm" type="password" class="form-control col-9 mt-1" name="password_confirmation"  placeholder="비밀번호 재입력" required>
                            <small class="form-text text-muted">로그인하실 비밀번호를 입력해주세요.</small>
                        </div>

                        <hr class="my-3">

                        <div class="form-group">
                            <label>이름</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="이름 입력하세요" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <small class="form-text text-muted">이름을 기입해주세요.</small>
                        </div>

                        <div class="form-group">
                            <label>이메일</label>
                            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="이메일 입력하세요" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <small class="form-text text-muted">이메일을 입력해주세요</small>
                        </div>

                        <div class="form-inline">
                            <div class="custom-control custom-radio">
                                {{ Form::radio('usertype', App\Enums\UserType::Professor, true, array('id'=>App\Enums\UserType::Professor, 'class'=>'custom-control-input')) }}
                                <label class="custom-control-label" for="{{\App\Enums\UserType::Professor}}">교수</label>
                            </div>
                            <div class="custom-control custom-radio ml-5">
                                {{ Form::radio('usertype', App\Enums\UserType::Student, false, array('id'=>App\Enums\UserType::Student, 'class'=>'custom-control-input'))}}
                                <label class="custom-control-label" for="{{\App\Enums\UserType::Student}}">학생</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                회원가입
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
