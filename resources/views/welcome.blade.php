@extends('layouts.app')

@section('header')
    <div id="sliderWrapper">
        <div class="black-filter">

        </div>
        <div id="slider">
            <div class="item" style="background-image:url( {{ asset('/img/slide_03.jpg') }});"></div>
            <div class="item" style="background-image:url( {{ asset('/img/slide_04.jpg') }});"></div>
        </div>

    </div>
    <script>
        $('header #slider').bxSlider({
            'mode':'fade',
            'auto':true,
            'controls':false,
            'pager':false,
            'randomStart':true,
            'wrapperClass':'headerSliderWrapper'
        });
    </script>
@endsection
@section('content')
    <div id="header-band" class="w100 row no-gutters">
        <div class="col-4"></div>
        <div class="col"></div>
    </div>

    <div id="home-bookpeople" class="container">
        <div class="py-5 mt-5 display-4">
            초보 프로그래머를 위한<br>
            <b>교육용 코딩도우미 플랫폼</b>
        </div>
        <div class="mt-3 lead">
            누구든지 코딩할 수 있습니다.
        </div>
        <div class="mt-5">
            @auth
                @if(Auth::user()->usertype == App\Enums\UserType::Professor)
{{--            <a href="{{route('question.add')}}"><button type="button" class="btn btn-outline-secondary">대회 주최하기</button></a>--}}
                @else
            <a href="{{route('question.list')}}"><button type="button" class="btn btn-secondary">대회 참가하기</button></a>
                    @endif
                @endauth
        </div>
    </div>

@endsection
