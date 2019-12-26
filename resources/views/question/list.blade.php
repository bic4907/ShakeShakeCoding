@extends('layouts.app')

@section('title')
    List
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')

    <div class = "container">
        <div class="list-group py-5">
            @if(Auth::check() && Auth::user()->usertype == App\Enums\UserType::Professor)
            <div class="mt-5">
                <a href="{{route('question.add')}}"><button type="button" class="btn btn-outline-secondary float-right">문제 출제하기</button></a>
            </div>
            </br>
            @endif
            @foreach($questions as $question)
                <a href="{{route('submission.create', ['question_num' => $question->id])}}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$question->title}}</h5>
                        <small class="font-italic">@ {{ $question->created_at }}</small>
                    </div>

                    <small>Written by {{$question->name}}</small>
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
