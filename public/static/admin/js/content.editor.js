layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var editor;

    var options = {
        uploadJson: '/admin/upload/content/img',
        cssPath: '/static/home/css/content.css',
        width: '100%',
        height: '300px',
        items: [
            'selectall', '|',
            'undo', 'redo', '|',
            'formatblock', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'removeformat', '|',
            'insertorderedlist', 'insertunorderedlist', 'table', 'code', '|',
            'image', 'link', 'unlink', '|',
            'source', 'about'
        ],
        htmlTags: {
            span: ['.color', '.background-color'],
            a: ['id', 'class', 'href', 'target', 'name'],
            img: ['id', 'class', 'src', 'width', 'height', 'alt', 'title'],
            table: ['id', 'class'],
            div: ['id', 'class'],
            pre: ['id', 'class'],
            hr: ['id', 'class'],
            embed: ['id', 'class', 'src', 'width', 'height', 'type', 'loop', 'autostart', 'quality', 'align', 'wmode'],
            iframe: ['id', 'class', 'src', 'width', 'height'],
            'td,th': ['id', 'class'],
            'p,ol,ul,li,blockquote,h1,h2,h3,h4,h5,h6': ['id', 'class'],
            'br,tbody,tr,strong,b,sub,sup,em,i,u,strike,s,del': ['id', 'class'],
        },
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