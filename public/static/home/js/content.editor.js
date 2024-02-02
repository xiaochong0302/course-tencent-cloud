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
            'copy', 'plainpaste', 'wordpaste', '|',
            'formatblock', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'removeformat', '|',
            'insertorderedlist', 'insertunorderedlist', 'table', '|',
            'superscript', 'subscript', '|', 'image', 'link', 'unlink', '|',
            'source', 'about'
        ],
    };

    KindEditor.ready(function (K) {
        editor = K.create('#editor-textarea', options);
    });

    $('.kg-submit').on('click', function () {
        editor.sync();
    });

});