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

    if ($('#tab-friends').length > 0) {
        var $tabFriends = $('#tab-friends');
        helper.ajaxLoadHtml($tabFriends.data('url'), $tabFriends.attr('id'));
    }

    if ($('#tab-groups').length > 0) {
        var $tabGroups = $('#tab-groups');
        helper.ajaxLoadHtml($tabGroups.data('url'), $tabGroups.attr('id'));
    }

});