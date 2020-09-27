layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;
    var $teacherList = $('#teacher-list');

    helper.ajaxLoadHtml($teacherList.data('url'), $teacherList.attr('id'));

});