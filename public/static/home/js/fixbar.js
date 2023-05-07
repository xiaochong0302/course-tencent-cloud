layui.use(['jquery', 'helper', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var showQQDialog = function () {
        window.open('https://wpa.qq.com/msgrd?v=3&uin=' + window.contact.qq + '&site=qq&menu=yes');
    }

    var showWechatCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.wechat + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    }

    var showTouTiaoCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.toutiao + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    }

    var bars = [];

    if (window.contact.qq) {
        bars.push({
            type: 'qq',
            icon: 'layui-icon-login-qq',
        });
    }

    if (window.contact.wechat) {
        bars.push({
            type: 'wechat',
            icon: 'layui-icon-login-wechat',
        });
    }

    util.fixbar({
        bars: bars,
        click: function (type) {
            if (type === 'qq') {
                showQQDialog();
            } else if (type === 'wechat') {
                showWechatCode();
            }
        }
    });

    $('.icon-wechat').on('click', function () {
        showWechatCode();
    });

    $('.icon-toutiao').on('click', function () {
        showTouTiaoCode();
    });

});