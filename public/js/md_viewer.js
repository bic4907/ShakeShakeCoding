function MarkdownConverterComponent($selector) {



    this.$_targetForm = $('textarea[name='+ $($selector).data('target') + ']')
    this.$_modalBtn = $($selector)
    this._modalToken = generate_token(5);
    this._modalId = '#mdviewer__' + this._modalToken
    var self = this

    $($selector).css({'cursor':'pointer'})

    this.$_modalBtn.on('click', function(e) {

        if($(self._modalId).length == 0) {
            output =
                '<div class="modal fade" id="mdviewer__' + self._modalToken + '" tabindex="-1" role="dialog" aria-labelledby="Markdown Viewer" aria-hidden="true">\n' +
                '  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">\n' +
                '    <div class="modal-content">\n' +
                '      <div class="modal-header">\n' +
                '        <h5 class="modal-title" id="exampleModalLongTitle">미리보기</h5>\n' +
                '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                '          <span aria-hidden="true">&times;</span>\n' +
                '        </button>\n' +
                '      </div>\n' +
                '      <div class="modal-body markdown-body" style=" overflow-y:auto;max-height: 700px;">\n' +
                '      </div>\n' +
                '      <div class="modal-footer">\n' +
                '        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>\n' +
                '      </div>\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>'
            $('body').append(output)
        }

        console.log(self.$_targetForm);
        $.ajax({
            url: '/api/mdconvert',
            method: 'POST',
            data: $.param({content: $(self.$_targetForm).val() }),
            success: function (data) {
                $(self._modalId + ' .modal-body').html(data)

                try {
                    MathJax.Hub.Config({
                        tex2jax: {inlineMath: [['$', '$'], ['\\(', '\\)']]}
                    });
                } catch {

                }

                $(self._modalId).modal('show')

            },
            error: function (request, status, error) {
                $(self._modalId + ' .modal-body').val(error)
                console.log(error)
            }
        })







        e.stopPropagation()





    })


}



(function($) {
    $.fn.MarkDownViewer = function() {

        $.each(this, function(index, item){
            var mdInstance =  new MarkdownConverterComponent($(item));



        });

    };
}(jQuery));
