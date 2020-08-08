layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var $textarea = $('#vditor-textarea');

    var vditor = new Vditor('vditor', {
        minHeight: 300,
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

    /**
     * 取消提交表单行为
     */
    $('body').on('click', '.vditor-preview__action > button', function () {
        return false;
    });

    /**
     * 同步编辑器内容到表单
     */
    $('.kg-submit').on('click', function () {
        $textarea.val(vditor.getValue());
    });

});