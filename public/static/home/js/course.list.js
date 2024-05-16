layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $courseList = $('#course-list');

    helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));

});