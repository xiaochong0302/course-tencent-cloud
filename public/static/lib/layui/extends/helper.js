layui.define(['jquery', 'layer'], function (exports) {

    var MOD_NAME = 'helper';
    var $ = layui.jquery;
    var layer = layui.layer;

    var helper = {};

    helper.ajaxLoadHtml = function (url, target) {
        var $target = $('#' + target);
        var html = '<div class="loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i></div>';
        $target.html(html);
        $.get(url, function (html) {
            $target.html(html);
        });
    };

    helper.checkLogin = function (callback) {
        if (window.user.id === '0') {
            layer.msg('继续操作前请先登录', {icon: 2, anim: 6});
            return false;
        }
        callback();
    };

    helper.getRequestId = function () {
        var id = Date.now().toString(36);
        id += Math.random().toString(36).substr(3);
        return id;
    };

    exports(MOD_NAME, helper);
});