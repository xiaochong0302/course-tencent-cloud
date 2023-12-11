layui.use(['jquery', 'form', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var helper = layui.helper;

    var $courseList = $('#sidebar-course-list');
    if ($courseList.length > 0) {
        helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));
    }

    var $articleList = $('#sidebar-article-list');
    if ($articleList.length > 0) {
        helper.ajaxLoadHtml($articleList.data('url'), $articleList.attr('id'));
    }

    var $questionList = $('#sidebar-question-list');
    if ($questionList.length > 0) {
        helper.ajaxLoadHtml($questionList.data('url'), $questionList.attr('id'));
    }

    form.on('submit(search)', function (data) {
        if (data.field.query === '') {
            return false;
        }
    });

});