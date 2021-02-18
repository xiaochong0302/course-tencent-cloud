layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;
    var $giftList = $('#gift-list');

    helper.ajaxLoadHtml($giftList.data('url'), $giftList.attr('id'));

});