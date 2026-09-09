layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    if ($('#tab-study-courses').length > 0) {
        var $tabStudyCourses = $('#tab-study-courses');
        helper.ajaxLoadHtml($tabStudyCourses.data('url'), $tabStudyCourses.attr('id'));
    }

});
