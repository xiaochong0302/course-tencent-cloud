layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var element = document.getElementById('preview');
    var markdown = element.innerHTML;
    var options = {
        lazyLoadImage: true,
        markdown: {
            autoSpace: true,
            chinesePunct: true
        }
    };

    Vditor.preview(element, markdown, options);

    layer.ready(function () {
        layer.load(1);
    });

    setTimeout(function () {
        $(element).removeClass('layui-hide');
        layer.closeAll();
    }, 1000);

});