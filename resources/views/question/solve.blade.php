@extends('layouts.light')


@section('content')
    <div class="problem-solve-container">
        <nav class="problem-solve-nav container-fluid">
            <div class="row">
                <div class="col-auto">
                    <a href="{{ route('question.list') }}">
                         < 문제 목록
                    </a>
                </div>
                <div class="col">
                    <span class="title lead">{{ $qTitle ?? '' }}</span>
                </div>
            </div>
        </nav>
        <problem-solve-component
            v-bind:question="{{ $question }}"
            v-bind:submission="{{ $submission }}"
        >
        </problem-solve-component>
    <div>

@endsection


