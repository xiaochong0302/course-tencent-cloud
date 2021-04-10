layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $articleList = $('#article-list');
    var $hotAuthorList = $('#hot-author-list');

    helper.ajaxLoadHtml($articleList.data('url'), $articleList.attr('id'));
    helper.ajaxLoadHtml($hotAuthorList.data('url'), $hotAuthorList.attr('id'));

});