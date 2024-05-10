layui.use(['jquery','layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

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
        layer.msg('内容已经复制到剪贴板');
    });

});