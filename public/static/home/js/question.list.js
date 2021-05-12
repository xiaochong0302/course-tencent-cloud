layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $questionList = $('#question-list');
    var $sidebarMyTags = $('#sidebar-my-tags');
    var $sidebarHotQuestions = $('#sidebar-hot-questions');
    var $sidebarTopAnswerers = $('#sidebar-top-answerer');

    if ($questionList.length > 0) {
        helper.ajaxLoadHtml($questionList.data('url'), $questionList.attr('id'));
    }

    if ($sidebarMyTags.length > 0) {
        helper.ajaxLoadHtml($sidebarMyTags.data('url'), $sidebarMyTags.attr('id'));
    }

    if ($sidebarHotQuestions.length > 0) {
        helper.ajaxLoadHtml($sidebarHotQuestions.data('url'), $sidebarHotQuestions.attr('id'));
    }

    if ($sidebarTopAnswerers.length > 0) {
        helper.ajaxLoadHtml($sidebarTopAnswerers.data('url'), $sidebarTopAnswerers.attr('id'));
    }

});