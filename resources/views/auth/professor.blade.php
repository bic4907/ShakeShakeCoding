@extends('layouts.app')

@section('title')
    Professor My List
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')

    <div class = "container">
        <div class="list-group py-5">
            @foreach($questionListData as $questionData)
                <a href="{{route('question.view', ['question_id' => $questionData['questionId']])}}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">과제 제목 : {{$questionData['title']}}</h5>
                        <small>정답률 : {{ $questionData['correctRate']}}</small>
                    </div>

                    <small>제출한 학생 수 : {{ $questionData['studentCount']}}</small>
                </a>
            @endforeach
        </div>
    </div>


    @if ($questionListData->lastPage() > 1)

        <ul class="pagination justify-content-center py-3">
            <li class="page-item {{ ($questionListData->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="/mypage/question{{ $questionListData->url($questionListData->currentPage() - 1) }}" tabindex="-1">이전</a>
            </li>
            @for ($i = 1; $i <= $questionListData->lastPage(); $i++)
                <li class="page-item {{ ($questionListData->currentPage() == $i) ? ' active' : '' }}">
                    <a class="page-link" href="/mypage/question{{ $questionListData->url($i) }}"> {{$i}} </a>
                </li>
            @endfor
            <li class="page-item {{ ($questionListData->currentPage() == $questionListData->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="/mypage/question{{ $questionListData->url($questionListData->currentPage() + 1) }}">다음</a>
            </li>
        </ul>

    @endif
@endsection
