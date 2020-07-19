layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    /**
     * 咨询
     */
    $('.icon-help').on('click', function () {
        var url = $(this).parent().data('url');
        helper.checkLogin(function () {
            layer.open({
                type: 2,
                title: '课程咨询',
                content: [url, 'no'],
                area: ['640px', '300px']
            });
        });
    });

});