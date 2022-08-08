layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    if ($('#tab-courses').length > 0) {
        var $tabCourses = $('#tab-courses');
        helper.ajaxLoadHtml($tabCourses.data('url'), $tabCourses.attr('id'));
    }

    if ($('#tab-articles').length > 0) {
        var $tabArticles = $('#tab-articles');
        helper.ajaxLoadHtml($tabArticles.data('url'), $tabArticles.attr('id'));
    }

    if ($('#tab-questions').length > 0) {
        var $tabQuestions = $('#tab-questions');
        helper.ajaxLoadHtml($tabQuestions.data('url'), $tabQuestions.attr('id'));
    }

    if ($('#tab-answers').length > 0) {
        var $tabAnswers = $('#tab-answers');
        helper.ajaxLoadHtml($tabAnswers.data('url'), $tabAnswers.attr('id'));
    }

});
