@extends('layouts.default')


@section('content')

    <div>
        for i in range(10) :</br>
        sum += i</br>
        </br>
        print('sum = %d', sum)</br>
        </br>

        <button type="submit" onclick="blink_button()">블링크</button>
        <button type="submit" onclick="block_button()">블럭</button>

        <script type="text/javascript">
            function selectText() {
                var selectionText = "";
                if (document.getSelection) {
                    selectionText = document.getSelection();
                } else if (document.selection) {
                    selectionText = document.selection.createRange().text;
                }

                return String(selectionText);
            }

            function blink_button() {
                // alert(selectText());
                var param = {
                    blink: selectText()
                }
                console.log(param)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "/create/",
                    data: param,
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }

            function block_button() {
                // alert(selectText());
                var param = {
                    block: selectText()
                }
                console.log(param)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "/create",
                    data: param,
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }
        </script>

    </div>


@endsection
