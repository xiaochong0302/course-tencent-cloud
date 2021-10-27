layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var $textarea = $('#vditor-textarea');

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
        mode: 'sv',
        minHeight: 450,
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
            url: '/upload/content/img',
            linkToImgUrl: '/upload/remote/img',
            max: 10 * 1024 * 1024,
            accept: 'image/*',
            headers: {
                'X-Csrf-Token': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (editor, responseText) {
                var json = JSON.parse(responseText);
                var img = '![](' + json.data.src + ')';
                vditor.insertValue(img);
            }
        },
        value: $textarea.val(),
        placeholder: '请输入内容描述'
    });

    $(window).bind('beforeunload', function () {
        return '内容尚未保存，确定离开此页面吗？';
    });

    /**
     * 同步编辑器内容到表单
     */
    $('.kg-submit').on('click', function () {
        $textarea.val(vditor.getValue());
        $(window).unbind('beforeunload');
    });

});