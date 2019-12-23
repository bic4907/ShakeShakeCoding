@extends('layouts.app')

@section('title')
    Page Not Found
@endsection

@section('header')
@section('header-bg'){{ asset('/img/triangular-300.jpg') }}@endsection
@include('layouts.header')
@endsection

@section('content')
<div class="container text-center py-5 my-5">

    <p class="display-4 pt-5 mt-5">404</p>

    <p class="lead">요청하신 페이지를 찾을 수 없습니다</p>

    <div class="py-5 my-3">
        <a onclick="history.back();"><button type="button" class="btn btn-outline-secondary" style="width:200px;">뒤로가기</button></a>
        <a href="{{route('home')}}"><button type="button" class="btn btn-outline-primary" style="width:200px;">홈</button></a>
    </div>
</div>
@endsection
