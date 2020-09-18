layui.use(['jquery'], function () {

    var $ = layui.jquery;
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

    setTimeout(function () {
        $(element).removeClass('layui-hide');
    }, 500);

});