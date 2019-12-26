
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

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active">정답 작성</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active">
                        <textarea class="form-control my-3" rows="30" placeholder="정답 코드를 입력해주세요" id="msg"></textarea>
                </div>
            </div>
            <!-- 저장버튼 -->
            <hr class="mb-4">
            <div class="pt-3">
                <button class="btn btn-primary btn-lg btn-block" type="submit" onclick="save_code()">저장</button>
            </div>

        </div>
    </div>

    <script>
        function save_code() {
            var param = {
                text: $("textarea#msg").val().replace(/(?:\r\n|\r|\n)/g, '<br>').replace(/(?: )/g, '&nbsp;')
            }
            console.log(param)
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "/question/add",
                data: param,
                success: function (data) {
                },
                error: function (xhr, status, error) {
                    alert(error);
                }
            });
        }
    </script>
@endsection
