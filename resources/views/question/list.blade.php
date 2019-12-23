@extends('layouts.default')

@section('title')
    List
@endsection

@section('header')
@endsection

@section('content')

    <div class = "container">
        <div class="list-group py-5">
            @foreach($questions as $question)
                <a href="{{route('question.view', ['question_num' => $question->id])}}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$question->id}}</h5>
                        <small class="font-italic">@ {{ $question->created_at }}</small>
                    </div>

                    <small>Written by {{ $question->professor_id }}</small>
                </a>
            @endforeach
        </div>
    </div>


    @if ($questions->lastPage() > 1)

        <ul class="pagination justify-content-center py-3">
            <li class="page-item {{ ($questions->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $questions->url($questions->currentPage() - 1) }}" tabindex="-1">이전</a>
            </li>
            @for ($i = 1; $i <= $questions->lastPage(); $i++)
                <li class="page-item {{ ($questions->currentPage() == $i) ? ' active' : '' }}">
                    <a class="page-link" href="{{ $questions->url($i) }}"> {{$i}} </a>
                </li>
            @endfor
            <li class="page-item {{ ($questions->currentPage() == $questions->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $questions->url($questions->currentPage() + 1) }}">다음</a>
            </li>
        </ul>

    @endif
@endsection
