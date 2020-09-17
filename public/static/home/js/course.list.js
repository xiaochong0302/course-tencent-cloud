layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $courseList = $('#course-list');

    helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));

    var $filter = $('.course-filter');

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