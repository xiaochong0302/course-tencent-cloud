layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    $('.btn-ask').on('click', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    var $questionList = $('#question-list');
    var $sidebarHotQuestions = $('#sidebar-hot-questions');
    var $sidebarTopAnswerers = $('#sidebar-top-answerers');

    if ($questionList.length > 0) {
        helper.ajaxLoadHtml($questionList.data('url'), $questionList.attr('id'));
    }

    if ($sidebarHotQuestions.length > 0) {
        helper.ajaxLoadHtml($sidebarHotQuestions.data('url'), $sidebarHotQuestions.attr('id'));
    }

    if ($sidebarTopAnswerers.length > 0) {
        helper.ajaxLoadHtml($sidebarTopAnswerers.data('url'), $sidebarTopAnswerers.attr('id'));
    }

});