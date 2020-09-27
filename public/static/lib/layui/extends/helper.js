layui.define(['jquery', 'layer'], function (exports) {

    var MOD_NAME = 'helper';
    var $ = layui.jquery;
    var layer = layui.layer;

    var helper = {};

    helper.getRequestId = function () {
        var id = Date.now().toString(36);
        id += Math.random().toString(36).substr(3);
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

    helper.cs = function () {
        layer.open({
            type: 2,
            title: '在线客服',
            area: ['600px', '560px'],
            content: ['/im/cs', 'no']
        });
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
        var shareUrl = 'http://v.t.sina.com.cn/share/share.php?';
        shareUrl += 'title=' + encodeURIComponent(title || document.title);
        shareUrl += '&url=' + encodeURIComponent(url || document.location);
        shareUrl += '&pic=' + encodeURIComponent(pic || '');
        window.open(shareUrl, '_blank');
    };

    exports(MOD_NAME, helper);
});