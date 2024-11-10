layui.use(['jquery', 'helper', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var showWechatCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.wechat + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showQQCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.qq + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showTouTiaoCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.toutiao + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showDouYinCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.douyin + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var bars = [];

    if (window.contact.wechat) {
        bars.push({
            type: 'wechat',
            icon: 'layui-icon-login-wechat',
        });
    }

    if (window.contact.qq) {
        bars.push({
            type: 'qq',
            icon: 'layui-icon-login-qq',
        });
    }

    util.fixbar({
        bars: bars,
        click: function (type) {
            if (type === 'wechat') {
                showWechatCode();
            } else if (type === 'qq') {
                showQQCode();
            }
        }
    });

    $('.icon-wechat').on('click', function () {
        showWechatCode();
    });

    $('.icon-qq').on('click', function () {
        showQQCode();
    });

    $('.icon-toutiao').on('click', function () {
        showTouTiaoCode();
    });

    $('.icon-douyin').on('click', function () {
        showDouYinCode();
    });

});