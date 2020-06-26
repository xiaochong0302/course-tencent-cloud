layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    if ($('#tab-courses').length > 0) {
        var $tabCourses = $('#tab-courses');
        helper.ajaxLoadHtml($tabCourses.data('url'), $tabCourses.attr('id'));
    }

    if ($('#tab-users').length > 0) {
        var $tabUsers = $('#tab-users');
        helper.ajaxLoadHtml($tabUsers.data('url'), $tabUsers.attr('id'));
    }

});