layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $userList = $('#user-list');
    var $activeUserList = $('#active-user-list');

    helper.ajaxLoadHtml($userList.data('url'), $userList.attr('id'));
    helper.ajaxLoadHtml($activeUserList.data('url'), $activeUserList.attr('id'));

});