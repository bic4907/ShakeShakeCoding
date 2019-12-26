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
    <div>

        <div id="notice-edit">


            <div class="container my-5 pb-5">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active">빈칸</a>
                    </li>
                    <li class="nav-item">
                        <div class="nav nav-tabs list-group mb-3" style="border-bottom-width: 0px;">
                            <a class="nav-item nav-links list-group-item list-group-item-action d-flex justify-content-between"
                               data-toggle="tab" href="#block">블럭
                            </a>
                        </div>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="getEverything">
                        {!!$description!!}
                    </div>
                </div>

                <hr class="mb-4">
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

                <!-- 저장버튼 -->
                    <div class="tab-pane fade active show" id="blink">
                        @include('question.editBlink')
                    </div>
                    <div class="tab-pane fade" id="block">
                        @include('question.editBlock')
                    </div>
                    <div class="pt-3">
                        <button class="btn btn-primary" type="submit"
                                onclick="submitProblem()">제출
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <script type="text/javascript">
            function submitProblem() {
                var param = {
                    text: $("#getEverything").html()
                }
                console.log(param)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "/question/edit/{{$problem_num}}",
                    data: param,
                    success: function (data) {
                        window.location.href =  "/question/list";
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }

                wfSel = {};
            (function (_self) {
                var _sel = window.getSelection();
                if (_sel) {
                    _self = {
                        getTEXT: function () {
                            var _range = window.getSelection().getRangeAt(0);
                            return _sel.toString();
                        },
                        getHTML: function () {
                            var _range = window.getSelection().getRangeAt(0),
                                _content = _range.cloneContents(),
                                _span = document.createElement('span');
                            _span.appendChild(_content);
                            return _span.innerHTML;
                        },
                        insert(_before, _after) {
                            _before = _before ? _before : '';
                            _after = _after ? _after : '';
                            this.replace(_before + wfSel.getHTML() + _after);
                        },
                        replace: function (text) {
                            var _range = window.getSelection().getRangeAt(0);
                            var _node = document.createElement('span');
                            _node.innerHTML = text;
                            if (_node) _node = _node.childNodes[0];
                            _range.deleteContents();
                            _range.insertNode(_node);
                        },
                        removeTag: function () {
                            this.replace(wfSel.getTEXT());
                        }
                    };
                    window.wfSel = _self;
                }
            })(wfSel);

            function randomString() {
                var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
                var string_length = 5;
                var randomstring = '';
                for (var i = 0; i < string_length; i++) {
                    var rnum = Math.floor(Math.random() * chars.length);
                    randomstring += chars.substring(rnum, rnum + 1);
                }
                return randomstring;
            }

        </script>
    </div>

@endsection
