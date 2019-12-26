
@extends('layouts.app')

@section('title')
    Question Add
@endsection

@section('header-title')
    {{ $header_title }}
@endsection

@section('header')
@include('layouts.short_head')
@endsection

@section('content')

    <div id="notice-edit">

        <div class="container my-5 pb-5">
            {{Form::open(array('action' => 'Question\RegisterController@add', 'method' => 'POST'))}}

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active">정답 작성</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active">
                    {{ Form::textarea('code', $question->code,array('class'=>'form-control my-3' ,'rows'=>'30', 'placeholder'=>'정답 코드를 입력해주세요')) }}
                </div>
            </div>
            <!-- 저장버튼 -->
            <hr class="mb-4">
            <div class="pt-3">
                <button class="btn btn-primary btn-lg btn-block" type="submit">저장</button>
            </div>


            {{ Form::close() }}
        </div>


    </div>


@endsection
