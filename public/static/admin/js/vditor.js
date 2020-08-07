layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var $textarea = $('#vditor-textarea');

    var vditor = new Vditor('vditor', {
        minHeight: 420,
        outline: false,
        resize: {
            enable: true
        },
        cache: {
            enable: false
        },
        preview: {
            markdown: {
                chinesePunct: true,
                autoSpace: true
            }
        },
        counter: {
            enable: true,
            max: 60000
        },
        upload: {
            url: '/admin/upload/img/editor',
            max: 10 * 1024 * 1024,
            accept: 'image/*',
            headers: {
                'X-Csrf-Token': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (editor, responseText) {
                console.log(editor, responseText);
                var json = JSON.parse(responseText);
                var img = '![](' + json.data.src + ')';
                vditor.insertValue(img);
            }
        },
        value: $textarea.val()
    });

    $('.kg-submit').on('click', function () {
        $textarea.val(vditor.getValue());
    });

});