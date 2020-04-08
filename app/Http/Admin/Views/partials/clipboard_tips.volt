{{ js_include('lib/clipboard.min.js') }}

<script>

    layui.use(['layer'], function () {

        var layer = layui.layer;
        var clipboard = new ClipboardJS('.kg-copy');

        clipboard.on('success', function (e) {
            layer.msg('内容已经复制到剪贴板');
        });

    });

</script>