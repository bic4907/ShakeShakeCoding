@extends('layouts.app')

@section('title')
    My page
@endsection

@section('header')
@section('header-bg'){{ asset('/img/triangular-300.jpg') }}@endsection
@include('layouts.header')
@endsection

@section('content')
    <div id="competition-edit">

        <div class="container my-5 pb-5">

            <div class="row">
                <div class="col-md-3 order-md-1 mb-4 pt-5">
                    <div class="nav nav-tabs list-group mb-3" style="border-bottom-width: 0px;">

                        <a class="nav-item nav-links list-group-item list-group-item-action d-flex justify-content-between active" data-toggle="tab" href="#info">
                            <div>
                                <h6 class="my-0">기본 정보</h6>
                                <small class="text-muted">이메일, 이름</small>
                            </div>
                        </a>
                    </div>

                </div>
                <div class="col-md-8 order-md-2">

                    <div class="tab-content">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{session('success')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{session('error')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="tab-pane fade active show" id="info">
                            @include('auth.profile')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
