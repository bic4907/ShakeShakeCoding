@extends('layouts.app')

@section('content')
    <div>
        <div id="getEverything">
{{--            for i in range(10) :</br>--}}
{{--            sum += i</br>--}}
{{--            </br>--}}
{{--            print('sum = %d', sum)</br>--}}
{{--            </br>--}}
            {{$description}}
        </div>
        <button type="submit" onclick="wfSel.replace('[[input:'+randomString()+']]')">블링크</button>
        <button type="submit" onclick="wfSel.replace('[[!!'+wfSel.getTEXT()+'!!]]')">블럭</button>
        <button type="submit" onclick="something()">제출</button>
        {{--        <button type="submit" onclick="blink_button()">블링크</button>--}}
        {{--        <button type="submit" onclick="block_button()">블럭</button>--}}

        <script type="text/javascript">

            function something() {
                var param = {
                    text: $("#getEverything").html()
                }
                console.log(param)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "/create/{{$problem_num}}",
                    data: param,
                    success: function (data) {
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }

            /*
            function blink_button() {
                var param = {
                    blink: wfSel.getTEXT()
                }
                console.log(param)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "/create/{{$problem_num}}",
                    data: param,
                    success: function (data) {
                        wfSel.replace('[[input:'+randomString()+']]');
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }

            function block_button() {
                var param = {
                    block: wfSel.getTEXT()
                }
                console.log(param)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "/create/{{$problem_num}}",
                    data: param,
                    success: function (data) {
                        wfSel.replace('{' + param.block + '}');
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }
*/
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
