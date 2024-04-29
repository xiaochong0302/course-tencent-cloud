layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var editor;

    var options = {
        uploadJson: '/upload/content/img',
        cssPath: '/static/home/css/content.css',
        width: '100%',
        height: '300px',
        items: [
            'selectall', '|',
            'undo', 'redo', '|',
            'formatblock', 'formatblock', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'removeformat', '|',
            'insertorderedlist', 'insertunorderedlist', 'table', 'code', '|',
            'image', 'link', 'unlink', '|',
            'source', 'about'
        ],
        pasteType: 1,
        extraFileUploadParams: {
            csrf_token: $('meta[name="csrf-token"]').attr('content')
        }
    };

    KindEditor.ready(function (K) {
        editor = K.create('#editor-textarea', options);
    });

    $('.kg-submit').on('click', function () {
        editor.sync();
    });

});