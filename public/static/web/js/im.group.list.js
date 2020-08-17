layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;
    var $groupList = $('#group-list');

    helper.ajaxLoadHtml($groupList.data('url'), $groupList.attr('id'));

});