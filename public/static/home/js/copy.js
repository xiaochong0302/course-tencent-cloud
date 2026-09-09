layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    if ($('.markdown-body').length > 0) {
        var mdPres = $('pre');
        if (mdPres.length > 0) {
            mdPres.each(function () {
                var text = $(this).children('code').text();
                var btn = $('<span class="kg-copy">复制</span>').attr('data-clipboard-text', text);
                $(this).prepend(btn);
            });
        }
    }

    if ($('.ke-content').length > 0) {
        var kePres = $('pre');
        if (kePres.length > 0) {
            kePres.each(function () {
                var text = $(this).text();
                var btn = $('<span class="kg-copy">复制</span>').attr('data-clipboard-text', text);
                $(this).prepend(btn);
            });
        }
    }

    var clipboard = new ClipboardJS('.kg-copy');

    clipboard.on('success', function () {
        helper.checkLogin(function () {
            layer.msg('内容已经复制到剪贴板');
        });
    });

});