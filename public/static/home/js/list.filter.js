layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var $filter = $('.filter-wrap');

    $('.filter-toggle').on('click', function () {
        var $icon = $(this).find('.layui-icon');
        if ($icon.hasClass('layui-icon-up')) {
            $icon.removeClass('layui-icon-up').addClass('layui-icon-down');
            $filter.hide();
        } else {
            $icon.removeClass('layui-icon-down').addClass('layui-icon-up');
            $filter.show();
        }
    });

});