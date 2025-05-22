layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $courseList = $('#course-list');

    if ($courseList.length > 0) {
        helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));
    }

});
