<div>
    for i in range(10) :</br>
    sum += i</br>
    </br>
    print('sum = %d', sum)</br>
    </br>

    <form action="" method="post" id="blink">
        <input type="hidden" >
        <button type="submit" form="blink-form" onclick="button_click()">버튼</button>
    </form>

    <script type="text/javascript">
        function selectText() {
            var selectionText = "";
            if (document.getSelection) {
                selectionText = document.getSelection();
            } else if (document.selection) {
                selectionText = document.selection.createRange().text;
            }
            return selectionText;
        }

        function button_click() {
            // alert(selectText());
            var param = {
                blink : selectText()
            }

            $.ajax({
                type : "POST",
                url : "",
                dataType : 'json',
                contentType : 'application/json',
                data : param,
                success : successCall,
                error   : errorCall
            });
        }
    </script>

</div>
