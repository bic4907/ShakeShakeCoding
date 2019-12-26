@extends('layouts.app')

@section('title')
    Student My List
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')

    <div class = "container">
        <div class="list-group py-5">
            @foreach($submissionListData as $submissionData)
                <a href="{{route('question.view', ['question_id' => $submissionData['questionId']])}}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$submissionData['questionId']}}</h5>
                    </div>

                    <small>Written by {{ $submissionData['professorName']}}</small>
                </a>
            @endforeach
        </div>
    </div>


    @if ($submissionListData->lastPage() > 1)

        <ul class="pagination justify-content-center py-3">
            <li class="page-item {{ ($submissionListData->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="/mypage/submission{{ $submissionListData->url($submissionListData->currentPage() - 1) }}" tabindex="-1">이전</a>
            </li>
            @for ($i = 1; $i <= $submissionListData->lastPage(); $i++)
                <li class="page-item {{ ($submissionListData->currentPage() == $i) ? ' active' : '' }}">
                    <a class="page-link" href="/mypage/submission{{ $submissionListData->url($i) }}"> {{$i}} </a>
                </li>
            @endfor
            <li class="page-item {{ ($submissionListData->currentPage() == $submissionListData->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="/mypage/submission{{ $submissionListData->url($submissionListData->currentPage() + 1) }}">다음</a>
            </li>
        </ul>

    @endif
@endsection
