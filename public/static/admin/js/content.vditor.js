layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var $textarea = $('#vditor-textarea');
    var $form = $('form:has(#vditor-textarea)');

    var toolbar = [
        'emoji',
        'headings',
        'bold',
        'italic',
        'strike',
        'link',
        '|',
        'list',
        'ordered-list',
        'check',
        'outdent',
        'indent',
        '|',
        'quote',
        'line',
        'code',
        'inline-code',
        'insert-before',
        'insert-after',
        '|',
        'upload',
        'table',
        '|',
        'undo',
        'redo',
        '|',
        'fullscreen',
        'edit-mode',
        {
            name: 'more',
            toolbar: [
                'both',
                'export',
                'preview',
                'info',
                'help',
            ],
        }];

    var vditor = new Vditor('vditor', {
        cdn: '/static/lib/vditor',
        mode: 'sv',
        minHeight: 500,
        outline: false,
        toolbar: toolbar,
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
            },
            actions: []
        },
        fullscreen: {
            index: 9999
        },
        counter: {
            enable: true,
            max: 30000
        },
        upload: {
            url: '/admin/upload/content/img',
            linkToImgUrl: '/admin/upload/content/img/remote',
            max: 10 * 1024 * 1024,
            accept: 'image/*',
            headers: {
                'X-Csrf-Token': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (editor, responseText) {
                var json = JSON.parse(responseText);
                var img = '![](' + json.data.url + ')';
                vditor.insertValue(img);
            }
        },
        value: $textarea.val()
    });

    /**
     * 同步编辑器内容到表单
     */
    $('.kg-submit').on('click', function () {
        $textarea.val(vditor.getValue());
    });

    /**
     * 定时提交编辑器内容
     */
    setInterval(function () {
        $textarea.val(vditor.getValue());
        if (!$form.attr('action').includes('update')) return;
        if ($textarea.val().length > 30) {
            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                data: $form.serialize(),
            });
        }
    }, 15000);

});
