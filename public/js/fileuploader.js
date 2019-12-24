function UploaderComponent($selector) {
    this.$_content = null
    this.$_mkdir_modal = null
    this.$_directUrl_modal = null

    this._uploading = {}
    this._list_data = []
    this._current_uuid = null
    this._token = null
    this._current_location = []
    this._selected = null

    this.flag_refreshing = false

    this.$_uploader = $selector
    this._path = this.$_uploader.data('path')
    this.flag_refreshing = false;


    var self = this
    $.ajax({
        async: false,
        url : self._path + '/permission',

        success: function (data) {
            self.permission = JSON.parse(data)
        }
    })
}
UploaderComponent.prototype.drawForm = function() {
    var self = this
    self._token = generate_token(10)

    var formHtml = '';

    formHtml +=
        '<div class="fu__showcase">' +
        '<div class="fu__navbar">' +
            '<span class="fu__text-bar"></span>'

    if(self.permission['mkdir'] == true || self.permission['upload'] == true) {
        formHtml +=
            '<button type="button" class="f__submenu_btn btn btn-outline-secondary btn-sm float-right mt-3 mr-2" style="font-size:11px;">' +
                '<i class="fas fa-plus"></i>' +
            '</button>' +
            '<div style="position: relative;width:0px;height:0px;float: right;">'+
            '<div class="f__submenu">'

        if(self.permission['mkdir'] == true) {
            formHtml += '<a class="dropdown-item" onclick="$(\'#modal_mkdir_' + self._token + '\').modal(\'toggle\')">폴더만들기</a>'
        }
        if(self.permission['upload'] == true) {
            formHtml += '<a class="dropdown-item" onclick="$(\'#f_upload_' + self._token + '\').click()">파일업로드</a>'
        }
        formHtml += '</div>'
        formHtml += '</div>'

    }

    formHtml +=
        '</div>' +

        '<input name="files" id="f_upload_' + self._token + '" type="file" style="display:none;transform:none !important;" multiple />' +
        '<div class="row no-gutters fu__content">' +
        '<div class="f_list col-6">' +
        '</div>' +

        '<div class="f_info col-6">' +
        '<div class="f_info_header">' +
        '<div class="f_title lead"></div>' +
        '<div class="row no-gutters">' +
        '<table class="col">' +
        '<tr>' +
        '<th>파일크기</th><td class="f_size"></td>' +
        '</tr>' +
        '<tr>' +
        '<th>최근수정</th><td class="f_last_modified"></td>' +
        '</tr>' +
        '</table>' +

        '<div class="col-auto">' +
        '<button type="button" style="display:none;" class="btn_download btn btn-primary btn-sm mt-2"><i class="fas fa-file-download mr-2"></i>다운로드</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'

    this.$_uploader.html(formHtml)


    $('body').append(
        '<div class="modal fade" id="modal_mkdir_' + this._token + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +
        '  <div class="modal-dialog" role="document">\n' +
        '    <div class="modal-content">' +
        '      <div class="modal-header">' +
        '        <h5 class="modal-title" id="exampleModalLabel">새 폴더</h5>' +
        '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
        '        </button>' +
        '      </div>\n' +
        '      <div class="modal-body">\n' +
        '        <form>\n' +
        '          <div class="form-group">\n' +
        '            <input name="f_name" class="form-control" placeholder="폴더명을 입력해주세요">\n' +
        '          </div>\n' +
        '        </form>\n' +
        '      </div>\n' +
        '      <div class="modal-footer">\n' +
        '        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>\n' +
        '        <button type="button" class="btn btn-primary submit_btn">확인</button>\n' +
        '      </div>\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>'

    )
    $('body').append(
        '<div class="modal fade" id="modal_directUrl_' + this._token + '" tabindex="-1" role="dialog" aria-labelledby="directUrlModal" aria-hidden="true">\n' +
        '  <div class="modal-dialog" role="document">\n' +
        '    <div class="modal-content">\n' +
        '      <div class="modal-header">\n' +
        '        <h5 class="modal-title" id="exampleModalLabel">다운로드 주소</h5>\n' +
        '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
        '        </button>\n' +
        '      </div>\n' +
        '      <div class="modal-body">\n' +
        '        <form>\n' +
        '<div class="input-group mb-3">\n' +
        '  <input name="f_name" class="form-control" readonly>\n' +
        '  <div class="input-group-append">\n' +
        '    <button class="btn-copy btn btn-primary" type="button">복사</button>\n' +
        '  </div>\n' +
        '</div>'+
        '        </form>\n' +
        '      </div>\n' +
        '      <div class="modal-footer">\n' +
        '        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>\n' +
        '      </div>\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>'

    )

    this.$_mkdir_modal = $('#modal_mkdir_' + this._token)
    this.$_directUrl_modal = $('#modal_directUrl_' + this._token)

    this.$_directUrl_modal.click(function() {
        $(this).find('input[name=f_name]').select();
        document.execCommand("copy");
    })
    this.$_uploader.find('.fu__text-bar').text(
        this.$_uploader.data('title')
    )
    this.$_uploader.find('.f__submenu_btn').on('click touch', function (e) {
        self.$_uploader.find('.f__submenu').toggle()
    })
    this.$_content = this.$_uploader.find('.fu__content')
    this.$_content.on('click touch', function (e) {
        self.$_uploader.find('.f__submenu').hide()
    })
    this.$_content.find('.btn_download').on('click touch', function (e) {
        self.onDownloadClicked(e);
    })
    this.$_uploader.find('#f_upload_' + this._token).on('change', function(e) {
        $.each(e.target.files, function(i, e) {
            self.upload_file(e)
        })
        $(this).val('')
    })

    this.$_mkdir_modal.find('.submit_btn').on('click touch', function (e) {
        var folder_name = self.$_mkdir_modal.find('input[name=f_name]').val()
        self.makeDirectory(folder_name)
        self.$_mkdir_modal.modal('hide')
    })


}

UploaderComponent.prototype.refresh_list = function() {
    var self = this;
    if(this.flag_refreshing) return;
    $.ajax({
        url: this._path,
        data: {
            loc: JSON.stringify(self._current_location)
        },
        beforeSend: function(){
            self.flag_refreshing = true;
        },
        success: function (data) {
            self._list_data = data
            self.render();
        },
        complete: function(){
            self.flag_refreshing = false;
        }
    })
}

UploaderComponent.prototype.upload_file = function(file) {
    var self = this

    var formData = new FormData();
    formData.append("file", file)
    formData.append('loc', JSON.stringify(self._current_location))

    var upload_token = generate_token(10)
    var ajaxObj = $.ajax({
        type : "POST",
        url : self._path,
        data : formData,
        processData: false,
        contentType: false,

        beforeSend:function() {
            self._uploading[upload_token] = {
                uuid: upload_token,
                name: file.name,
                xhrObj: ajaxObj,
                progress:0.0
            }

            self.render()
        },

        success : function(data) {
            self.refresh_list()
            $.amaran({
                'message': '업로드 완료',
                'position': 'bottom right',
                'inEffect': 'slideRight'
            });
        },
        complete : function() {
            delete self._uploading[upload_token]
            self.render()
        },
        xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
                var percent = e.loaded * 100 / e.total;
                self.$_uploader.find('li.uploading[data-uuid=' + upload_token + '] .progress-bar').css({'width':percent.toString() + '%'})
                self._uploading[upload_token].progress = percent
            };
            return xhr;
        },
        error: function (request, status, error) {
            $.amaran({
                'message':  '업로드 실패<br>' + error,
                'position': 'bottom right',
                'inEffect': 'slideRight'
            });

        }
    });
}

UploaderComponent.prototype.render = function() {
    var self = this
    var output = ''
    output += '<ul>'

    if (self._current_location.length) {
        output += sprintf('<li data-type="%s" data-fname="%s"><i class="list__icon far fa-folder"></i>%s</li>', 'dir', '..', '..')
    }

    $.each(this._list_data.dir, function (_, e) {
        output += sprintf('<li data-type="%s" data-fname="%s"><i class="list__icon far fa-folder"></i>%s</li>', 'dir', e.name, e.name)
    })

    $.each(this._uploading, function (k, e) {
        output += sprintf('<li class="uploading" data-type="%s" data-uuid="%s"><i class="list__icon far fa-file"></i>%s<div class="progress-bar" style="width:%s%"></div></li>', 'uploading', e['uuid'], e['name'], e['progress'])
    })

    $.each(this._list_data.file, function (_, e) {
        output += sprintf('<li data-type="%s" data-uuid="%s"><i class="list__icon far fa-file"></i>%s</li>', 'file', e.uuid, e.name)
    })
    output += '</ul>'
    this.$_content.find('.f_list').html(output)

    // Event Listener
    this.$_content.find('li[data-type="file"]').on('click touch', function () {
        const uuid = $(this).data('uuid')

        var fileInfo = null;

        $.each(self._list_data.file, function (_, e) {
            if (e.uuid == uuid) {
                fileInfo = e
                return
            }
        })
        if (fileInfo) {
            self._current_uuid = uuid;
            self.displayFile(fileInfo)
        }
    })


    this.$_content.find('li[data-type="dir"]').on('click touch', function () {
        var dir_name = $(this).data('fname')
        if (dir_name != '..') {
            self._current_location.push(
                $(this).data('fname').toString()
            )
        } else {
            self._current_location.pop()
        }
        self.refresh_list()
    })
    this.$_content.find('li[data-type="file"]').on('contextmenu', function (e) {
        self._$selected = $(this)
        self.hideContextMenu()
        self.renderContextMenu(this)
        self.showContextMenu(e)

        return false;
    })
    this.$_content.find('li[data-type="dir"]').on('contextmenu', function (e) {
        self._$selected = $(this)
        self.hideContextMenu()
        self.renderContextMenu(this)
        self.showContextMenu(e)
        return false;
    })
    this.$_uploader.on('click touch', function () {
        self.hideContextMenu()
    })

}


UploaderComponent.prototype.hideContextMenu = function() {
    this.$_uploader.find('.f__contextMenu').hide()
}

UploaderComponent.prototype.displayFile = function(info) {
    this.$_content.find('.f_title').text(info.name)
    this.$_content.find('.f_size').text(formatBytes(info.size))
    this.$_content.find('.f_last_modified').text(getTimestampToDate(info.last_modified))
    this.$_content.find('.btn_download').show();
}

UploaderComponent.prototype.onDownloadClicked = function() {

    const dl_link = this._path + '/api/' + this._current_uuid;
    $.ajax({
        url: dl_link,
        success: function (data) {
            const ret = data

            if (ret.cmd == 'Ok') {
                $.amaran({
                    'message': '다운로드 시작 중',
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
                $('body').append(
                    sprintf('<iframe class="download_instance" style="display:none" src="%s" width="1" height="1"></iframe>', ret.param)
                )
            } else if(ret.cmd == 'FileNotFound') {
                $.amaran({
                    content: {
                        title: '에러',
                        message: '파일을 찾을 수 없습니다',
                        info: '&nbsp;',
                        icon: 'fas fa-exclamation-circle'
                    },
                    theme: 'awesome default'
                });
            }


        },
        error: function (request, status, error) {
            $.amaran({
                content: {
                    title: '다운로드 에러',
                    message: error,
                    info: '&nbsp;',
                    icon: 'fa fa-error'
                },
                theme: 'awesome default'
            });
        }
    })
}

UploaderComponent.prototype.makeDirectory = function(f_name) {
    var self = this
    const mkdir_link = this._path + '/mkdir';
    var _name = f_name
    $.ajax({
        url: mkdir_link,
        method: 'POST',
        data: $.param({name: f_name, loc: JSON.stringify(self._current_location)}),
        success: function (data) {
            const ret = data

            if (ret.cmd == 'Ok') {
                $.amaran({
                    'message': "디렉토리가 생성되었습니다",
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
                self.refresh_list()
                self.render()

            } else if (ret.cmd == 'ExistingDirectory') {
                $.amaran({
                    'message': '이미 존재하는 디렉토리입니다',
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
            } else if (ret.cmd == 'ValidationError') {
                $.amaran({
                    'message': data.param,
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
            }


        },
        error: function (request, status, error) {
            $.amaran({
                content: {
                    title: '에러',
                    message: error,
                    info: '&nbsp;',
                    icon: 'fa fa-error'
                },
                theme: 'awesome default'
            });
        }
    })
}

UploaderComponent.prototype.unzipFile = function(f_name) {
    var self = this
    const unzip_link = this._path + '/unzip';
    var _name = f_name
    $.ajax({
        url: unzip_link,
        method: 'POST',
        data: $.param({name: f_name, path:self._path}),
        success: function (data) {
            const ret = data

            if (ret.cmd == 'UnzipRequestSuccess') {
                $.amaran({
                    'message': '압축해제 요청성공',
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
            } else if (ret.cmd == 'UnzipRequestError') {
                $.amaran({
                    'message': "압축해제 요청실패",
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
            }


        },
        error: function (request, status, error) {
            $.amaran({
                content: {
                    title: '에러',
                    message: error,
                    info: '&nbsp;',
                    icon: 'fa fa-error'
                },
                theme: 'awesome default'
            });
        }
    })
}

UploaderComponent.prototype.getDirectURL = function(f_name) {

    var self = this
    const durl_link_link = this._path + '/directurl';
    var _name = f_name
    $.ajax({
        url: durl_link_link,
        method: 'GET',
        data: $.param({name: f_name}),
        success: function (data) {
            const ret = data

            if (ret.cmd == 'DirectUrlOK') {
                self.$_directUrl_modal.find('input[name=f_name]').val(data.param)
                self.$_directUrl_modal.modal('show')
            } else if (ret.cmd == 'DirectUrlFail') {
                $.amaran({
                    content: {
                        title: '에러',
                        message: 'URL생성이 거부되었습니다',
                        info: '&nbsp;',
                        icon: 'fa fa-error'
                    },
                    theme: 'awesome default'
                });
            }


        },
        error: function (request, status, error) {
            $.amaran({
                content: {
                    title: '에러',
                    message: error,
                    info: '&nbsp;',
                    icon: 'fa fa-error'
                },
                theme: 'awesome default'
            });
        }
    })
}

UploaderComponent.prototype.removeFile = function(f_name) {

    var self = this
    const rmfile_link = this._path + '/rmfile';
    $.ajax({
        url: rmfile_link,
        method: 'POST',
        data: $.param({name: f_name}),
        success: function (data) {
            const ret = data

            if (ret.cmd == 'FileDeleteOK') {
                $.amaran({
                    'message': '파일삭제 성공',
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
                self.refresh_list()
            } else if (ret.cmd == 'FileDeleteFail') {
                $.amaran({
                    'message': "파일삭제 실패",
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
            }
        },
        error: function (request, status, error) {
            $.amaran({
                content: {
                    title: '에러',
                    message: error,
                    info: '&nbsp;',
                    icon: 'fa fa-error'
                },
                theme: 'awesome default'
            });
        }
    })
}

UploaderComponent.prototype.removeDirectory = function(f_name) {

    var self = this
    const rmdir_link = this._path + '/rmdir';

    $.ajax({
        url: rmdir_link,
        method: 'post',
        data: $.param({name: f_name, loc:JSON.stringify(self._current_location)}),
        success: function (data) {
            const ret = data

            if (ret.cmd == 'DirectoryDeleteOK') {
                $.amaran({
                    'message': '디렉토리 삭제성공',
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
                self.refresh_list()
            } else if (ret.cmd == 'DirectoryDeleteFail') {
                $.amaran({
                    'message': "오류",
                    'position': 'bottom right',
                    'inEffect': 'slideRight'
                });
            }
        },
        error: function (request, status, error) {
            $.amaran({
                content: {
                    title: '에러',
                    message: error,
                    info: '&nbsp;',
                    icon: 'fa fa-error'
                },
                theme: 'awesome default'
            });
        }
    })
}

UploaderComponent.prototype.renderContextMenu = function(item) {
    var self = this

    self.$_uploader.find('.f__contextMenu').remove()

    var uls = '';
    var cnt_uls = 0;
    if($(item).data('type') == 'dir') {
        if (self.permission['rmdir'] == true) {
            uls += '<a class="f__contextMenu__delete dropdown-item">삭제</a>';
            cnt_uls++;
        }
    } else if($(item).data('type') == 'file') {
        if (self.permission['unzip'] == true) {
            uls += '<a class="f__contextMenu__unzip dropdown-item">압축풀기</a>'
            cnt_uls++;
        }
        if (self.permission['durl'] == true) {
            uls += '<a class="f__contextMenu__copyurl dropdown-item">URL복사</a>'
            cnt_uls++;
        }
        if (self.permission['rmf'] == true) {
            uls += '<a class="f__contextMenu__delete dropdown-item">삭제</a>'
            cnt_uls++;
        }
    }

    if(cnt_uls != 0) {
        $(item).append(
            '<ul class="f__contextMenu" style="display:none">'+
            uls +
            '</ul>'
        )
    }
    $('.f__contextMenu a.f__contextMenu__unzip').click(function() {
        self._current_uuid = self._$selected.data('uuid')
        self.unzipFile(self._current_uuid)
        self.hideContextMenu()
    })
    $('.f__contextMenu a.f__contextMenu__copyurl').click(function() {
        self._current_uuid = self._$selected.data('uuid')
        self.getDirectURL(self._current_uuid)
        self.hideContextMenu()
    })
    $('.f__contextMenu a.f__contextMenu__delete').click(function(e) {
        if($(self._$selected).data('type') == 'dir') {
            self.removeDirectory($(self._$selected).data('fname'))

        } else {
            self._current_uuid = self._$selected.data('uuid')
            self.removeFile(self._current_uuid)
        }
        self.hideContextMenu()
        e.stopPropagation()
    })
}

UploaderComponent.prototype.showContextMenu = function(e) {
    this.$_uploader.find('.f__contextMenu').show()
}

UploaderComponent.prototype.hideContextMenu = function() {
    this.$_uploader.find('.f__contextMenu').hide()
}



function listenNotification() {

    var self = this;

    if(typeof _UID != 'undefined' &&_UID != null) {
        if (typeof Echo != "undefined") {
            Echo.private('user.' + _UID)
                .listen('UnzipFailed', function(e) {

                    $.amaran({
                        content: {
                            title: '알림',
                            message: '지원하지 않는 파일입니다',
                            info: e.filename,
                            icon: 'fas fa-exclamation'
                        },
                        theme: 'awesome default'
                    });
                })
                .listen('UnzipSuccess', function(e) {

                    $.amaran({
                        content: {
                            title: '알림',
                            message: '압축해제가 완료되었습니다.',
                            info: e.filename,
                            icon: 'far fa-check-circle'
                        },
                        theme: 'awesome default'
                    });

                })
        }

    }

}



(function($) {
    $.fn.FileUploader = function() {

        $.each(this, function(index, item){
            var uInstance =  new UploaderComponent($(item));
            uInstance.drawForm()
            uInstance.refresh_list()

        });

        listenNotification()
    };
}(jQuery));
