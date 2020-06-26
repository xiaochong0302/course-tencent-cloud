layui.define(['jquery', 'element'], function (exports) {

    var MOD_NAME = 'helper';
    var $ = layui.jquery;
    var element = layui.element;

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

    exports(MOD_NAME, helper);
});