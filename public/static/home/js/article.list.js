layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $articleList = $('#article-list');
    var $sidebarHotAuthors = $('#sidebar-hot-authors');
    var $sidebarMyTags = $('#sidebar-my-tags');

    if ($articleList.length > 0) {
        helper.ajaxLoadHtml($articleList.data('url'), $articleList.attr('id'));
    }

    if ($sidebarMyTags.length > 0) {
        helper.ajaxLoadHtml($sidebarMyTags.data('url'), $sidebarMyTags.attr('id'));
    }
    
    if ($sidebarHotAuthors.length > 0) {
        helper.ajaxLoadHtml($sidebarHotAuthors.data('url'), $sidebarHotAuthors.attr('id'));
    }
    
});