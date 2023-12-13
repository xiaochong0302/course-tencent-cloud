layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    $('.btn-post').on('click', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    var $articleList = $('#article-list');
    var $sidebarTopAuthors = $('#sidebar-top-authors');

    if ($articleList.length > 0) {
        helper.ajaxLoadHtml($articleList.data('url'), $articleList.attr('id'));
    }

    if ($sidebarTopAuthors.length > 0) {
        helper.ajaxLoadHtml($sidebarTopAuthors.data('url'), $sidebarTopAuthors.attr('id'));
    }

});