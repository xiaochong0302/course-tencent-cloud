layui.use(['jquery', 'form', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var helper = layui.helper;

    var $courseList = $('#sidebar-course-list');
    if ($courseList.length > 0) {
        helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));
    }

    form.on('submit(search)', function (data) {
        if (data.field.query === '') {
            return false;
        }
    });

});
