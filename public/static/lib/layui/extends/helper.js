layui.define(['jquery', 'element', 'layer'], function (exports) {

    var MOD_NAME = 'helper';
    var $ = layui.jquery;
    var element = layui.element;
    var layer = layui.layer;

    var helper = {};

    helper.ajaxLoadHtml = function (url, target) {
        var $target = $('#' + target);
        var html = '<div class="loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i></div>';
        $target.html(html);
        $.get(url, function (html) {
            $target.html(html);
            element.init();
        });
    };

    helper.checkLogin = function () {
        if (window.koogua.user.id === '0') {
            layer.msg('继续操作前请登录或者注册', {icon: 2, anim: 6});
            return false;
        }
        return true;
    };

    helper.getRequestId = function () {
        var id = Date.now().toString(36);
        id += Math.random().toString(36).substr(3);
        return id;
    };

    exports(MOD_NAME, helper);
});