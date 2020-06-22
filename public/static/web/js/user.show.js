layui.use(['jquery', 'element'], function () {

    var $ = layui.jquery;

    if ($('#tab-courses').length > 0) {
        var $tabCourses = $('#tab-courses');
        layui.ajaxLoadHtml($tabCourses.attr('data-url'), $tabCourses.attr('id'));
    }
    if ($('#tab-favorites').length > 0) {
        var $tabFavorites = $('#tab-favorites');
        layui.ajaxLoadHtml($tabFavorites.attr('data-url'), $tabFavorites.attr('id'));
    }
    if ($('#tab-friends').length > 0) {
        var $tabFriends = $('#tab-friends');
        layui.ajaxLoadHtml($tabFriends.attr('data-url'), $tabFriends.attr('id'));
    }

});