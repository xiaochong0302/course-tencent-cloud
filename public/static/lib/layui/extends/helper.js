layui.define(['jquery', 'layer'], function (exports) {

    var MOD_NAME = 'helper';
    var $ = layui.jquery;
    var layer = layui.layer;

    var helper = {};

    helper.isEmail = function (email) {
        return /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(email);
    };

    helper.isPhone = function (phone) {
        return /^1(3|4|5|6|7|8|9)\d{9}$/.test(phone);
    };

    helper.getRequestId = function () {
        var id = Date.now().toString(36);
        id += Math.random().toString(36).substring(3);
        return id;
    };

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

    helper.wechatShare = function (qrcode) {
        var content = '<div class="qrcode"><img src="' + qrcode + '" alt="分享到微信"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    };

    helper.qqShare = function (title, url, pic) {
        var shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
        shareUrl += 'title=' + encodeURIComponent(title || document.title);
        shareUrl += '&url=' + encodeURIComponent(url || document.location);
        shareUrl += '&pics=' + pic;
        window.open(shareUrl, '_blank');
    };

    helper.weiboShare = function (title, url, pic) {
        var shareUrl = 'http://service.weibo.com/share/share.php?';
        shareUrl += 'title=' + encodeURIComponent(title || document.title);
        shareUrl += '&url=' + encodeURIComponent(url || document.location);
        shareUrl += '&pic=' + encodeURIComponent(pic || '');
        window.open(shareUrl, '_blank');
    };

    exports(MOD_NAME, helper);
});