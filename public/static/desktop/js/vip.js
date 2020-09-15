layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    $('.btn-order').on('click', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    if ($('#tab-discount-courses').length > 0) {
        var $tabDiscountCourses = $('#tab-discount-courses');
        helper.ajaxLoadHtml($tabDiscountCourses.data('url'), $tabDiscountCourses.attr('id'));
    }

    if ($('#tab-free-courses').length > 0) {
        var $tabFreeCourses = $('#tab-free-courses');
        helper.ajaxLoadHtml($tabFreeCourses.data('url'), $tabFreeCourses.attr('id'));
    }

    if ($('#tab-users').length > 0) {
        var $tabUsers = $('#tab-users');
        helper.ajaxLoadHtml($tabUsers.data('url'), $tabUsers.attr('id'));
    }

});