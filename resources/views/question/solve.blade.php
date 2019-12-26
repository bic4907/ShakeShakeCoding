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
                    <span class="title lead">{{ $question->title ?? '' }}</span>
                </div>
                <div class="col-auto flaot-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                        문제보기
                    </button>
                </div>
            </div>
        </nav>
        <problem-solve-component
            v-bind:question="{{ $question }}"
            v-bind:submission="{{ $submission }}"
        >
        </problem-solve-component>
    <div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ $question->title ?? '' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ $question->description ?? '' }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                    </div>
                </div>
            </div>
        </div>
@endsection


